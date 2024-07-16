@extends('layouts.app')

@section('title', 'Dashboard Table')

@section('content')

<body>
    <h1>List of Tables</h1>
    <ul>
        @foreach ($tables as $table)
            <li>
                {{ $table->TABLE_NAME }}
                <a href="{{ route('audits.show', $table->TABLE_NAME) }}">Audit</a>
            </li>
        @endforeach
    </ul>
</body>