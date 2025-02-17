@extends('app.layout')
@section('title') Novo Caderno @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Dados do Caderno</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form action="{{ route('create-notebook') }}" method="POST" class="row mt-3">
                    @csrf

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nomeie seu caderno:" required>
                            <label for="name">Nomeie seu caderno</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                        <div class="btn-group w-100">
                            <select id="swal-jury" name="jury_id[]" placeholder="Escolha uma Banca" class="w-100">
                                <option value="" selected>Escolha uma Banca</option>
                                <option value="all">Todas as bancas</option>
                                @foreach($juries as $jury)
                                    <option value="{{ $jury->id }}">{{ $jury->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-dark" title="Limpar" id="btnClearJury"><i class="bi bi-backspace"></i></button>
                        </div>
                    </div>
                   
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 no-filter mb-3">
                        <div class="btn-group w-100 mb-3">
                            <input type="text" name="searchSubject" class="form-control" placeholder="Pesquisar conteúdos" title="Pesquisar conteúdos">
                            <button type="button" class="btn btn-dark" title="Pesquisar"><i class="bi bi-search"></i></button>
                        </div>

                        @foreach ($subjects as $subject)
                            <div class="form-check form-check-subject">
                                <input class="form-check-input subject-checkbox" type="checkbox" 
                                    name="subjects[]" value="{{ $subject->id }}"
                                    data-questions='{{ $subject->totalQuestions() }}'
                                    id='subject-{{ $subject->id }}'
                                    data-topics='@json($subject->topics)'
                                    id-resolved='{{ $subject->questionResolved($subject->id) }}' 
                                    id-resolved-parent='{{ $subject->questionResolvedParent($subject->id) }}'
                                    id-fail='{{ $subject->questionFail() }}'>
                                <label class="form-check-label" for="subject-{{ $subject->id }}"><b>{{ $subject->name }}</b></label>
                            </div>
                    
                            <div class="ps-3 topics" id="topics-{{ $subject->id }}" style="display: none;">
                                @foreach ($subject->topics as $topic)
                                    <div class="form-check form-check-subject">
                                        <input class="form-check-input topic-checkbox" type="checkbox"
                                            name="topics[{{ $subject->id }}][]" value="{{ $topic->id }}"
                                            data-subject="{{ $subject->id }}"
                                            data-questions="{{ $topic->totalQuestions() }}"
                                            data-jury-questions='@json($subject->questionsByJury($topic->id))'
                                            id="topic-{{ $topic->id }}"
                                            id-resolved='{{ $topic->questionResolved($topic->id) }}' 
                                            id-fail='{{ $topic->questionFail() }}'
                                            id-favorite='{{ $topic->totalQuestionsFavoriteForTopic($topic->id) }}'>
                                        <label class="form-check-label" for="topic-{{ $topic->id }}">{{ $topic->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <small class="btn btn-dark mt-3 w-100" id="question-count">Foram encontradas: 0 questões</small>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filter" value="remove_question_resolved" id="removeQuestionResolved">
                            <label class="form-check-label" for="removeQuestionResolved">Eliminar questões já <b>Resolvidas</b></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filter" value="show_question_fail" id="showQuestionFail">
                            <label class="form-check-label" for="showQuestionFail">Mostrar apenas as que eu já <b>Errei</b></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="filter" value="show_question_favorite" id="showQuestionFavorite">
                            <label class="form-check-label" for="showQuestionFavorite">Mostrar apenas as questões <b>Favoritas</b></label>
                        </div>
                    </div>  
                    
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-floating mb-3">
                            <input type="number" name="number" class="form-control" id="questions" placeholder="N° questões:" required>
                            <label for="questions">N° questões</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-1">
                        <button type="submit" class="btn btn-lg btn-outline-dark w-100">Criar Caderno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const selectElement = document.querySelector("#swal-jury");
            const tomSelect = new TomSelect(selectElement, {
                create: false,
                maxItems: 1000,
                onInitialize: function () {
                    this.clear();
                },
                onChange: function (value) {
                    if (value.includes("all")) {
                        const allOptions = Object.keys(this.options).filter(opt => opt !== "all");
                        this.setValue(["all", ...allOptions], true);
                    } 
                    else if (!value.includes("all") && value.length === 0) {
                        this.clear();
                    }
                }
            });

            const btnClearJury = document.querySelector("#btnClearJury");
            btnClearJury.addEventListener("click", function () {
                tomSelect.clear();
            });

            let totalQuestions = 0;
            let lastChecked = null;
            const questionCountElement = document.getElementById("question-count");
            const questionsInput = document.getElementById("questions");

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

            const searchInput = document.querySelector('input[name="searchSubject"]');
            searchInput.addEventListener("input", function () {
                
                const searchTerm = searchInput.value.trim().toLowerCase();
                const subjectItems = document.querySelectorAll('.form-check-subject');

                subjectItems.forEach(function (subjectItem) {
                    const subjectLabel = subjectItem.querySelector("label").textContent.toLowerCase();
                    if (subjectItem.closest('.no-filter') && subjectItem.querySelector('label')) {
                        const subjectLabel = subjectItem.querySelector("label").textContent.toLowerCase();
                        if (subjectLabel.includes(searchTerm)) {
                            subjectItem.style.display = "block";
                        } else {
                            subjectItem.style.display = "none";
                        }
                    }
                });
            });

            function updateQuestionCount() {
                totalQuestions = 0;
                const filterResolved = document.getElementById("removeQuestionResolved").checked;
                const filterFail = document.getElementById("showQuestionFail").checked;
                const filterFavorite = document.getElementById("showQuestionFavorite").checked;

                const selectedJuries = Array.from(document.getElementById("swal-jury").selectedOptions)
                    .map(option => option.value)
                    .filter(value => value !== "all");

                document.querySelectorAll('.subject-checkbox:checked').forEach(function (subjectCheckbox) {
                    let subjectQuestions = parseInt(subjectCheckbox.getAttribute('data-questions')) || 0;
                    const subjectResolved = parseInt(subjectCheckbox.getAttribute('id-resolved')) || 0;
                    const subjectFail = parseInt(subjectCheckbox.getAttribute('id-fail')) || 0;
                    const subjectFavorite = parseInt(subjectCheckbox.getAttribute('id-favorite')) || 0;

                    let subjectHasTopic = false;
                    const associatedTopics = JSON.parse(subjectCheckbox.getAttribute('data-topics'));

                    associatedTopics.forEach(function (topic) {
                        const topicCheckbox = document.getElementById('topic-' + topic.id);
                        if (topicCheckbox && topicCheckbox.checked) {
                            let topicQuestions = parseInt(topicCheckbox.getAttribute('data-questions')) || 0;
                            const topicResolved = parseInt(topicCheckbox.getAttribute('id-resolved')) || 0;
                            const topicFail = parseInt(topicCheckbox.getAttribute('id-fail')) || 0;
                            const topicFavorite = parseInt(topicCheckbox.getAttribute('id-favorite')) || 0;
                            const juryQuestions = JSON.parse(topicCheckbox.getAttribute('data-jury-questions') || "{}");

                            if (selectedJuries.length > 0) {
                                topicQuestions = selectedJuries.reduce((sum, jury) => {
                                    return sum + (juryQuestions[jury] || 0);
                                }, 0);
                            }

                            if (filterResolved) {
                                topicQuestions -= topicResolved;
                            }
                            if (filterFail) {
                                topicQuestions = topicFail;
                            }
                            if (filterFavorite) {
                                topicQuestions = topicFavorite;
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
                        if (filterFavorite) {
                            subjectQuestions = subjectFavorite;
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
                        const juryQuestions = JSON.parse(topicCheckbox.getAttribute('data-jury-questions') || "{}");

                        if (selectedJuries.length > 0) {
                            topicQuestions = selectedJuries.reduce((sum, jury) => {
                                return sum + (juryQuestions[jury] || 0);
                            }, 0);
                        }

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

                if (questionsInput) {
                    questionsInput.setAttribute("max", totalQuestions);
                    if (parseInt(questionsInput.value) > totalQuestions) {
                        questionsInput.value = totalQuestions;
                    }
                }
            }

            document.getElementById("swal-jury").addEventListener("change", updateQuestionCount);

            document.querySelectorAll('.subject-checkbox').forEach(function (subjectCheckbox) {
                subjectCheckbox.addEventListener('change', function () {
                    const subjectId = subjectCheckbox.value;
                    const topicsDiv = document.getElementById('topics-' + subjectId);

                    topicsDiv.querySelectorAll('.topic-checkbox').forEach(function (topicCheckbox) {
                        topicCheckbox.checked = subjectCheckbox.checked;
                    });

                    if (subjectCheckbox.checked) {
                        topicsDiv.style.display = 'block';
                    } else {
                        topicsDiv.style.display = 'none';
                        topicsDiv.querySelectorAll('.topic-checkbox').forEach(function (topicCheckbox) {
                            topicCheckbox.checked = false;
                        });
                    }

                    updateQuestionCount();
                });
            });

            document.querySelectorAll('.subject-checkbox, .topic-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', updateQuestionCount);
            });

            document.querySelectorAll('input[name="filter"]').forEach(function (radio) {
                radio.addEventListener('change', updateQuestionCount);
            });

            questionsInput.addEventListener('input', function () {
                if (parseInt(questionsInput.value) > totalQuestions) {
                    questionsInput.value = totalQuestions;
                }
            });

            updateQuestionCount();
        });
    </script>
@endsection