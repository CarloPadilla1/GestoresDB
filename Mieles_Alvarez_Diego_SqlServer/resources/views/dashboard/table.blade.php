@extends('layouts.app')

@section('title', 'Dashboard Table')

@section('content')
    <h2>{{ $name }}</h2>
    @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success">
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                @foreach ($columns as $item)
                    <th>{{ $item }}</th>
                @endforeach
                <th>Relations</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    @foreach ($columns as $column)
                        <td>{{ $item->{$column} }}</td>
                    @endforeach
                    <td>
                        @foreach ($item->getRelations() as $relationName => $relation)
                            @if ($relation instanceof Illuminate\Database\Eloquent\Collection)
                                <strong>{{ ucfirst($relationName) }}:</strong>
                                <ul>
                                    @if ($relation->isEmpty())
                                        <li>No related items</li>
                                    @endif
                                    @foreach ($relation as $relatedItem)
                                        {{ ($relatedItem->name ? $relatedItem->name : ($relatedItem->policy_number ?
                                                $relatedItem->policy_number  : $relatedItem->reason_for_appointment)) ?? 'N/A'
                                        }}
                                    @endforeach

                                </ul>
                            @else
                            {{-- <strong>{{ ucfirst($relationName) }}:</strong> {{ ($relation?->name ? $relation->name : ($relation?->policy_number ?
                                $relation?->policy_number  : ($relation?->reason_for_appointment ? $relation?->reason_for_appointment : ($relation?->state ? $relation?->state : ($relation?->result ? $relation?->result : ($relation?->medicine ? $relation?->medicine : $relation->diagnosis)) )))) ?? 'N/A'
                            }} --}}
                            @endif
                        @endforeach
                    </td>
                    <td>

                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $item->{$columns[0]} }}">Edit</button>
                        <!-- Modal -->
                        <div class="modal fade" id="editModal{{ $item->{$columns[0]} }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $item->{$columns[0]} }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $item->{$columns[0]} }}">Edit Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                    <form action="{{ route('item.update', ['name' => $name, 'id' => $item->{$columns[0]}]) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            @foreach ($dataForm as $key => $value)
                                                <div class="form-group">
                                                    <label for="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                                    <input type="text" name="{{ $key }}" id="{{ $key }}" value="{{ $item->{$key} }}" class="form-control">
                                                </div>
                                            @endforeach
                                    </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $item->{$columns[0]} }}').submit();" class="btn btn-danger btn-sm">Delete</a>
                        <!-- Formulario oculto -->
                        <form id="delete-form-{{ $item->{$columns[0]} }}" action="{{ route('item.destroy', ['name' => $name, 'id' => $item->{$columns[0]} ]) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>




    <form action="{{ route('table.insert', $name) }}" method="POST" class="mt-4">
        <h3>Aggregate {{$name}}</h3>
        @csrf
        <div class="form-row">
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    <p>{{ Session::get('error') }}</p>
                </div>
            @endif
            @foreach ($dataForm as $key => $item)
                @if (strpos($key, 'id') !== false)
                    <div class="form-group col-md-6">
                        <label for="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                        <select name="{{ $key }}" id="{{ $key }}" class="form-control">
                            @if($item)
                                @foreach ($item as $id => $name)

                                        <option value="{{ $id }}">{{ $name }}</option>

                                @endforeach
                            @else
                                <option value="" disabled selected>No exists elements</option>
                            @endif
                        </select>
                    </div>
                @else
                    <div class="form-group col-md-6">
                        <label for="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                        <input type="text" name="{{ $key }}" id="{{ $key }}" value="{{ old($key) }}" class="form-control">
                    </div>
                @endif
            @endforeach
        </div>
        <div>
            <p>Format date: "Y-m-d"</p>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection

