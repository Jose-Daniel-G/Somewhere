@extends('adminlte::page')

@section('title', 'Profile')
@section('css')
    <style>
        body {
            background: #fff;
        }

        h1 {
            font-size: 48px;
            color: #065f46;
        }

        h2 {
            font-size: 28px;
            color: #333;
        }

        .subtitle {
            font-size: 18px;
            color: #444;
        }

        .text-box {
            border: 2px solid #065f46;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            background: #f9f9f9;
            line-height: 1.6;
            font-size: 16px;
            color: #333;
        }

        .text-box span {
            color: #065f46;
            font-weight: bold;
        }

        #input-area {
            margin-top: 15px;
            font-size: 16px;
        }

        #input-area:disabled {
            background: #e9ecef;
        }

        .correct {
            color: green;
            font-weight: bold;
        }

        .incorrect {
            color: red;
            font-weight: bold;
        }

        #text-display {
            display: flex;
            flex-wrap: wrap;
            gap: 0;
            /* elimina los espacios entre spans */
        }

        #text-display span {
            display: inline-block;
            white-space: pre;
            /* respeta espacios reales */
        }
    </style>
@endsection
@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop

@section('content')
    <section class="content">
        <div class="container" style="max-width: 700px;">
            <!-- Título -->
            <h1 class="fw-bold">Somewhere</h1>
            <h2 class="fw-semibold">Typing Test</h2>

            <!-- Subtítulo -->
            <p class="subtitle mt-3">Try our free typing test here!</p>

            <!-- Temporizador -->
            <div class="d-flex align-items-center text-primary fw-bold mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span id="timer">1:00</span>
            </div>

            {{-- Form --}}
            <form action="{{ route('typing.test.result') }}" method="POST" id="typingForm">
                @csrf
                <div id="text-display" class="border border-success rounded p-3 mt-3 bg-light"
                    style="font-size: 18px; line-height: 1.6;">
                    @foreach (str_split($originalText) as $char)
                        <span>{{ $char }}</span>
                    @endforeach

                    <input type="hidden" name="original" id="original" value="{{ $originalText }}">

                    @foreach (str_split($originalText) as $char)
                        <span>{{ $char }}</span>
                    @endforeach
                </div>

                <!-- Botón para iniciar -->
                <button type="button" id="startBtn" class="btn btn-success mt-3">Start Test</button>


                <!-- 👇 importante: guardar lo que el usuario escribe -->
                <!-- Área de escritura -->
                <textarea id="input-area" class="form-control border-primary mt-3" rows="4" name="typed"
                    placeholder="Start typing..." disabled></textarea>
                <input type="hidden" name="time" id="timeField" value="60">

            </form>
            <!-- Área de escritura -->
        </div>
        </div>
    </section>
@stop
@section('js')
    <script>
        let timeLeft = 60;
        let countdown = null;
        const timer = document.getElementById("timer");
        const inputArea = document.getElementById("input-area");
        const timeField = document.getElementById("timeField");
        const textDisplay = document.querySelectorAll("#text-display span");
        const startBtn = document.getElementById("startBtn");
        const form = document.getElementById("typingForm");

        function submitTypingForm() {
            clearInterval(countdown);
            inputArea.disabled = true;

            // Asegurarse de que el textarea tenga valor en el formulario
            const typedInput = document.createElement('input');
            typedInput.type = 'hidden';
            typedInput.name = 'typed';
            typedInput.value = inputArea.value;
            form.appendChild(typedInput);

            // Tiempo real empleado
            const timeSpentInput = document.createElement('input');
            timeSpentInput.type = 'hidden';
            timeSpentInput.name = 'timeSpent';
            timeSpentInput.value = 60 - timeLeft;
            form.appendChild(timeSpentInput);

            form.submit();
        }
        // ✅ Timer solo arranca cuando presionas el botón
        startBtn.addEventListener("click", () => {
            // Reset por si se reinicia
            clearInterval(countdown);
            timeLeft = 60;
            timer.textContent = "1:00";
            timeField.value = timeLeft;

            startBtn.disabled = true; // deshabilitamos botón start
            inputArea.disabled = false; // habilitamos área de escritura
            inputArea.focus();

            countdown = setInterval(() => {
                timeLeft--;
                timeField.value = timeLeft;

                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timer.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft <= 0) {
                    submitTypingForm();
                }
            }, 1000);
        });

        // ✅ Resaltar letras mientras escribe
        inputArea.addEventListener("input", () => {
            const typedText = inputArea.value.split("");

            textDisplay.forEach((span, index) => {
                const char = typedText[index];

                if (char == null) {
                    span.classList.remove("text-success", "text-danger");
                } else if (char === span.textContent) {
                    span.classList.add("text-success");
                    span.classList.remove("text-danger");
                } else {
                    span.classList.add("text-danger");
                    span.classList.remove("text-success");
                }
            });
            // Enviar si el usuario terminó el texto
            const original = form.querySelector('input[name="original"]').value;
            if (inputArea.value === original) {
                submitTypingForm();
            }
        });
    </script>


@endsection
