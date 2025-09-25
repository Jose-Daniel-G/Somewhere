<?php

namespace App\Http\Controllers;

use App\Models\TypingTest;
use Illuminate\Http\Request;

class TypingTestController extends Controller
{
    public function index()
    {
        $originalText = file_get_contents(resource_path('views/typing/oak_tree.txt'));

        return view('typing.index', compact('originalText'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        $time = (int) $request->time;
        if ($time <= 0) { $time = 60; }     // valor por defecto
       
        // tiempo empleado: si se envía 'timeSpent', usarlo, si no, usar el tiempo máximo
        $timeSpent = $request->timeSpent ?? $time; // en segundos
        $timeMinutes = max($timeSpent / 60, 1/60); // mínimo 1 segundo para evitar división por cero

        $typed       = $request->typed;     // texto escrito por el usuario
        $original    = $request->original;  // texto original

        $totalChars = strlen($typed);
        $correctChars = 0;

        for ($i = 0; $i < min(strlen($typed), strlen($original)); $i++) {
            if ($typed[$i] === $original[$i]) {
                $correctChars++;
            }
        }

        $errors = $totalChars - $correctChars;

        $grossWPM = ($totalChars / 5) / $timeMinutes;
        $accuracy = $totalChars > 0 ? ($correctChars / $totalChars) * 100 : 0;
        $netWPM = $grossWPM - ($errors / $timeMinutes);

        $status = ($accuracy >= 80 && $netWPM >= 40) ? "Passed" : "Failed";

        return view('typing.show', compact('grossWPM', 'accuracy', 'netWPM', 'status'));
    }


    public function edit(TypingTest $typingTest)
    {
        //
    }

    public function update(Request $request, TypingTest $typingTest)
    {
        //
    }

    public function destroy(TypingTest $typingTest)
    {
        //
    }
}
