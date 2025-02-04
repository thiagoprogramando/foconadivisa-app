@if ($unansweredQuestions->hasPages())
    <div class="text-center mt-3">
        <ul class="pagination">
            {{-- Botão Anterior --}}
            @if ($unansweredQuestions->previousPageUrl())
                <li class="page-item">
                    <a class="page-link" href="{{ $unansweredQuestions->previousPageUrl() }}">&laquo; Anterior</a>
                </li>
            @endif

            {{-- Página Atual --}}
            <li class="page-item active">
                <span class="page-link">
                    Questão {{ $nextQuestionNumber }} de {{ $totalQuestions }}
                </span>
            </li>

            {{-- Botão Próximo --}}
            @if ($unansweredQuestions->nextPageUrl())
                <li class="page-item">
                    <a class="page-link" href="{{ $unansweredQuestions->nextPageUrl() }}">Próxima &raquo;</a>
                </li>
            @endif
        </ul>
    </div>
@endif
