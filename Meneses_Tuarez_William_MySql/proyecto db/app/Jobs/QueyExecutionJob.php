<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueyExecutionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $query;
    /**
     * Create a new job instance.
     * 
     */
    
    public function __construct($query)
    {
        //
        $this->query = $query;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
{
    $startTime = microtime(true);

    try {
        DB::statement($this->query);

        $executionTime = round((microtime(true) - $startTime) * 1000); // Tiempo en milisegundos

        // Registrar la ejecución en la tabla de auditoría
        DB::table('query_executions')->insert([
            'query_text' => $this->query,
            'execution_time' => $executionTime,
            'created_at' => now(),
        ]);

    } catch (\Exception $e) {
        // Registrar el error en el archivo de registro
        Log::error('Error al ejecutar el job: ' . $e->getMessage());

        // También puedes volver a lanzar la excepción si quieres que el job falle explícitamente
        throw $e;
    }
}

}
