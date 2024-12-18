@extends('app.layout')
@section('title') 
    Questão: <a href="">{{ $question->id }}</a> - {{ Str::limit(strip_tags($question->question_text), 40) }}
@endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">

        <h6 class="card-title">
            <a href="#">Conteúdo/Tópico:</a> {{ $question->subject->name ?? '---' }}/{{ $question->topic->name ?? '---' }}
        </h6>        

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Questão</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    @if($question->responsesCountGeneral())
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <h5 class="card-title text-center">Desempenho Geral</h5>
                            <canvas id="doughnutChart" style="max-height: 200px;"></canvas>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
        
                                    const errorsCount = @json($question->wrongCountGeneral());
                                    const correctCount = @json($question->correctCountGeneral());
        
                                    new Chart(document.querySelector('#doughnutChart'), {
                                        type: 'doughnut',
                                        data: {
                                            labels: [
                                                'Erros',
                                                'Acertos'
                                            ],
                                            datasets: [{
                                                label: 'Total',
                                                data: [errorsCount, correctCount],
                                                backgroundColor: [
                                                '#DC3545',
                                                '#198754'
                                                ],
                                                hoverOffset: 4
                                            }]
                                        }
                                    });
                                });
                            </script>
                        
                            <ul class="list-group mt-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total de resoluções:
                                    <span class="badge bg-dark rounded-pill">{{ $question->responsesCountGeneral(null, null, $question->id) }} vezes</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Acertos:
                                    <span class="badge bg-success rounded-pill">{{ $question->correctCountGeneral(null, null, $question->id) }} vezes</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Erros:
                                    <span class="badge bg-danger rounded-pill">{{ $question->wrongCountGeneral(null, null, $question->id) }} vezes</span>
                                </li>
                            </ul>
                        </div>
                    @endif
                    @if($question->responsesCountGeneral())
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <h5 class="card-title text-center">Alternativas Marcadas</h5>
                            <div id="barChart" style="min-height: 400px;" class="echart"></div>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {

                                    const labels = @json(array_keys($answerDistribution));
                                    const data = @json(array_values($answerDistribution));
                                    const correctOptionIndex = ({{ $question->correctOption()->option_number }} - 1);

                                    echarts.init(document.querySelector("#barChart")).setOption({
                                        xAxis: {
                                            type: 'category',
                                            data: labels 
                                        },
                                        yAxis: {
                                            type: 'value'
                                        },
                                        series: [{
                                            type: 'bar',
                                            data: data.map((value, index) => ({
                                                value: value,
                                                itemStyle: {
                                                    color: index === correctOptionIndex ? '#198754' : '#DC3545'
                                                }
                                            }))
                                        
                                        }]
                                    });
                                });
                            </script>
                        </div>
                    @endif

                    @if($question->responsesCount(Auth::user()->id))
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <h5 class="card-title text-center">Meu Desempenho</h5>
                            <canvas id="my" style="max-height: 200px;"></canvas>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
        
                                    const errorsCount = @json($question->wrongCountGeneral());
                                    const correctCount = @json($question->correctCountGeneral());
        
                                    new Chart(document.querySelector('#my'), {
                                        type: 'doughnut',
                                        data: {
                                            labels: [
                                                'Erros',
                                                'Acertos'
                                            ],
                                            datasets: [{
                                                label: 'Total',
                                                data: [errorsCount, correctCount],
                                                backgroundColor: [
                                                '#DC3545',
                                                '#198754'
                                                ],
                                                hoverOffset: 4
                                            }]
                                        }
                                    });
                                });
                            </script>
                        
                            <ul class="list-group mt-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total de resoluções:
                                    <span class="badge bg-dark rounded-pill">{{ $question->responsesCount(Auth::user()->id, null, $question->id) }} vezes</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Acertos:
                                    <span class="badge bg-success rounded-pill">{{ $question->correctCount(Auth::user()->id, null, $question->id) }} vezes</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Erros:
                                    <span class="badge bg-danger rounded-pill">{{ $question->wrogCount(Auth::user()->id, null, $question->id) }} vezes</span>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection