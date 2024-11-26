@extends('app.layout')
@section('title') Caderno: {{ $notebook->name }} @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Filtros</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form action="{{ route('update-notebook') }}" method="POST" class="row mt-3">
                    @csrf
                    <input type="hidden" name="id" value="{{ $notebook->id }}">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nomeie seu caderno:" value="{{ $notebook->name }}" required>
                            <label for="name">Nomeie seu caderno</label>
                        </div>
                    </div>
                   
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                        <div class="btn-group w-100 mb-3">
                            <input type="text" name="searchSubject" class="form-control" placeholder="Pesquisar conteúdos" title="Pesquisar conteúdos">
                            <button type="button" class="btn btn-dark" title="Pesquisar"><i class="bi bi-search"></i></button>
                        </div>

                        @foreach ($subjectsFromPlan as $subject)
                            <div class="form-check">
                                <input class="form-check-input subject-checkbox" type="checkbox" 
                                    name="subjects[]" value="{{ $subject->id }}"
                                    data-questions='{{ $subject->totalQuestions() }}'
                                    id='subject-{{ $subject->id }}'
                                    data-topics='@json($subject->topics)'
                                    id-resolved='{{ $subject->questionResolved() }}' 
                                    id-fail='{{ $subject->questionFail() }}'
                                    @if (in_array($subject->id, $notebookSubjects->pluck('id')->toArray())) checked @endif>
                                <label class="form-check-label" for="subject-{{ $subject->id }}">{{ $subject->name }}</label>
                            </div>

                            <div class="ps-3 topics" id="topics-{{ $subject->id }}" style="display: none;">
                                @foreach ($subject->topics as $topic)
                                    <div class="form-check">
                                        <input class="form-check-input topic-checkbox" type="checkbox"
                                            name="topics[]" value="{{ $topic->id }}"
                                            data-subject="{{ $subject->id }}"
                                            data-questions="{{ $topic->totalQuestions() }}"
                                            id="topic-{{ $topic->id }}"
                                            id-resolved='{{ $topic->questionResolved() }}' 
                                            id-fail='{{ $topic->questionFail() }}'
                                            @if (in_array($topic->id, $notebookTopics->pluck('id')->toArray())) checked @endif
                                            @if (in_array($subject->id, $notebookSubjects->pluck('id')->toArray())) checked @endif>
                                        <label class="form-check-label" for="topic-{{ $topic->id }}">{{ $topic->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <small class="btn btn-dark mt-5 w-100" id="question-count">Foram encontradas: 0 questões</small>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filter" value="remove_question_resolved" id="removeQuestionResolved">
                            <label class="form-check-label" for="removeQuestionResolved">Eliminar questões já resolvidas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filter" value="show_question_fail" id="showQuestionFail">
                            <label class="form-check-label" for="showQuestionFail">Mostrar apenas as que eu já errei</label>
                        </div>
                    </div>      
                    
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-floating mb-3">
                            <input type="number" name="number" class="form-control" id="questions" placeholder="N° questões:" required>
                            <label for="questions">N° questões</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-1">
                        <button type="submit" class="btn btn-lg btn-outline-dark w-100">Atualizar Caderno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const questionCountElement = document.getElementById("question-count");
            let totalQuestions = 0;
            let lastChecked = null;
            let filterResolved = false;
            let filterFail = false;

            const searchButton = document.querySelector('.btn.btn-dark');
            searchButton.addEventListener("click", function (event) {
                
                event.preventDefault();
                const searchTerm = $('input[name="searchSubject"]').val().trim().toLowerCase();
                const subjectItems = document.querySelectorAll('.form-check');

                subjectItems.forEach(function (subjectItem) {

                    const subjectLabel = subjectItem.querySelector("label").textContent.toLowerCase();
                    if (subjectLabel.includes(searchTerm)) {
                        subjectItem.style.display = "block";
                    } else {
                        subjectItem.style.display = "none";
                    }
                });
            });

            document.querySelectorAll('input[name="filter"]').forEach(function (radio) {
                radio.addEventListener('click', function () {
                    if (lastChecked === this) {
                        this.checked = false;
                        lastChecked = null;
                    } else {
                        lastChecked = this;
                    }

                    updateQuestionCount();
                });
            });

            function updateQuestionCount() {
                totalQuestions = 0;
                const filterResolved = document.getElementById("removeQuestionResolved").checked;
                const filterFail = document.getElementById("showQuestionFail").checked;

                document.querySelectorAll('.subject-checkbox:checked').forEach(function (subjectCheckbox) {
                    let subjectQuestions = parseInt(subjectCheckbox.getAttribute('data-questions')) || 0;
                    const subjectResolved = parseInt(subjectCheckbox.getAttribute('id-resolved')) || 0;
                    const subjectFail = parseInt(subjectCheckbox.getAttribute('id-fail')) || 0;

                    let subjectHasTopic = false;
                    const associatedTopics = JSON.parse(subjectCheckbox.getAttribute('data-topics'));

                    associatedTopics.forEach(function (topic) {
                        const topicCheckbox = document.getElementById('topic-' + topic.id);
                        if (topicCheckbox && topicCheckbox.checked) {
                            let topicQuestions = parseInt(topicCheckbox.getAttribute('data-questions')) || 0;
                            const topicResolved = parseInt(topicCheckbox.getAttribute('id-resolved')) || 0;
                            const topicFail = parseInt(topicCheckbox.getAttribute('id-fail')) || 0;

                            if (filterResolved) {
                                topicQuestions -= topicResolved;
                            }
                            if (filterFail) {
                                topicQuestions = topicFail;
                            }

                            totalQuestions += Math.max(topicQuestions, 0);
                            subjectHasTopic = true;
                        }
                    });

                    if (!subjectHasTopic) {
                        if (filterResolved) {
                            subjectQuestions -= subjectResolved;
                        }
                        if (filterFail) {
                            subjectQuestions = subjectFail;
                        }

                        totalQuestions += Math.max(subjectQuestions, 0);
                    }
                });

                document.querySelectorAll('.topic-checkbox:checked').forEach(function (topicCheckbox) {
                    const subjectId = topicCheckbox.getAttribute('data-subject');
                    if (!document.querySelector(`#subject-${subjectId}:checked`)) {
                        let topicQuestions = parseInt(topicCheckbox.getAttribute('data-questions')) || 0;
                        const topicResolved = parseInt(topicCheckbox.getAttribute('id-resolved')) || 0;
                        const topicFail = parseInt(topicCheckbox.getAttribute('id-fail')) || 0;

                        if (filterResolved) {
                            topicQuestions -= topicResolved;
                        }
                        if (filterFail) {
                            topicQuestions = topicFail;
                        }

                        totalQuestions += Math.max(topicQuestions, 0);
                    }
                });

                questionCountElement.textContent = `Foram encontradas: ${totalQuestions} questões`;
            }

            document.querySelectorAll('.subject-checkbox').forEach(function(subjectCheckbox) {
                subjectCheckbox.addEventListener('change', function() {
                    const subjectId = subjectCheckbox.value;
                    const topicsDiv = document.getElementById('topics-' + subjectId);

                    if (subjectCheckbox.checked) {
                        topicsDiv.style.display = 'block';
                    } else {
                        topicsDiv.style.display = 'none';
                        topicsDiv.querySelectorAll('.topic-checkbox').forEach(function(topicCheckbox) {
                            topicCheckbox.checked = false;
                        });
                    }

                    updateQuestionCount();
                });
            });

            @foreach ($subjectsFromPlan as $subject)
            
                if (document.getElementById('subject-{{ $subject->id }}')) {
                    document.getElementById('subject-{{ $subject->id }}').checked = @if(in_array($subject->id, $notebookSubjects->pluck('id')->toArray())) true @else false @endif;

                    const topicsDiv = document.getElementById('topics-{{ $subject->id }}');
                    if (document.getElementById('subject-{{ $subject->id }}').checked) {
                        topicsDiv.style.display = 'block';
                    } else {
                        topicsDiv.style.display = 'none';
                    }
                }

                @foreach ($subject->topics as $topic)
                    if (document.getElementById('topic-{{ $topic->id }}')) {
                        document.getElementById('topic-{{ $topic->id }}').checked = @if(in_array($topic->id, $notebookTopics->pluck('id')->toArray())) true @else false @endif;
                    }
                @endforeach
            @endforeach

            document.querySelectorAll('.subject-checkbox, .topic-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', updateQuestionCount);
            });

            document.querySelectorAll('input[name="filter"]').forEach(function(radio) {
                radio.addEventListener('change', updateQuestionCount);
            });

            updateQuestionCount();
        });
    </script>
@endsection