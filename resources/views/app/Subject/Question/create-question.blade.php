@extends('app.layout')
@section('title') Cadastro de Questões @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <h3>{{ $subject->name }}</h3>
        <hr>
        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Questão</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">

            <div class="tab-pane active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <form action="{{ route('update-question') }}" method="POST" class="row mt-3 m-5">
                    @csrf
                    <input type="hidden" name="id" value="{{ $question->id }}">

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                        <select id="swal-topic" name="subject_id" placeholder="Escolha um tópico (Opcional)">
                            <option value="{{ $question->subject_id }}" selected>@if(empty($question->subject_id)) Escolha um tópico (Opcional) @else {{ $question->topic->name ?? '---' }} @endif</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <textarea name="question_text" class="form-control" placeholder="Questão" id="question" style="height: 200px;">{{ $question->question_text }}</textarea>
                            <label for="question">Questão:</label>
                        </div>
                    </div>
                    @php
                        $letters = ['A', 'B', 'C', 'D', 'E'];
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                            <div class="form-floating mb-2">
                                <input type="text" name="option_{{ $i }}" class="form-control" id="option_{{ $i }}" placeholder="{{ $i }} - Opção" value="{{ $options[$i]->option_text ?? '' }}">
                                <label for="option_{{ $i }}">{{ $letters[$i - 1] }})</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_correct_{{ $i }}" id="is_correct_{{ $i }}" {{ isset($options[$i]) && $options[$i]->is_correct ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_correct_{{ $i }}">Correta</label>
                            </div>
                        </div>
                    @endfor

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <textarea name="comment_text" class="form-control" placeholder="Comentários do Professor:" id="comment_text" style="height: 100px;">{{ $question->comment_text }}</textarea>
                            <label for="comment_text">Comentários do Professor:</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <button type="submit" class="btn btn-outline-success w-100 mb-2">Salvar</button>
                        <a href="{{ route('create-question', ['topic' => $subject->id]) }}" class="btn btn-outline-primary w-100 mb-2">Nova Questão</a>
                    </div>
                </form>
            </div>
        </div>
        
    </div>

    <script>
        new TomSelect("#swal-topic",{
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
        document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    document.querySelectorAll('.form-check-input').forEach(function(otherCheckbox) {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            });
        });
    </script>

@endsection