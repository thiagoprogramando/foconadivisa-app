@extends('app.layout')
@section('title') 
    Questão: <a href="">{{ $question->id }}</a> - {{ html_entity_decode(Str::limit(strip_tags($question->question_text), 70)) }}
@endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">

        <h6>
            <b>Conteúdo/Tópico: </b><a href="#">{{ $question->subject->parent->name ?? $question->subject->name }}/{{ $question->topic->name ?? '---' }}</a> <br>
            <b>Banca: </b><a href="#">{{ $question->jury->name }}</a>
        </h6>        

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Questão</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <h5 class="card-title text-center">Desempenho Geral</h5>
                        <canvas id="doughnutChart" style="max-height: 200px;"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const errorsCount = @json($question->wrongCountGeneral(null, null, $question->id));
                                const correctCount = @json($question->correctCountGeneral(null, null, $question->id));

                                const ctx = document.querySelector('#doughnutChart');

                                const chartConfig = {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Erros', 'Acertos'],
                                        datasets: [{
                                            label: 'Total',
                                            data: [errorsCount, correctCount],
                                            backgroundColor: ['#DC3545', '#198754'],
                                            hoverOffset: 4
                                        }]
                                    },
                                    options: {
                                        plugins: {
                                            tooltip: {
                                                enabled: errorsCount > 0 || correctCount > 0,
                                            }
                                        }
                                    },
                                    plugins: [{
                                        id: 'noDataPlugin',
                                        beforeDraw: (chart) => {
                                            const { datasets } = chart.data;
                                            const totalData = datasets[0].data.reduce((a, b) => a + b, 0);

                                            if (totalData === 0) {
                                                const ctx = chart.ctx;
                                                const width = chart.width;
                                                const height = chart.height;

                                                ctx.save();
                                                ctx.textAlign = 'center';
                                                ctx.textBaseline = 'middle';
                                                ctx.font = '16px Arial';
                                                ctx.fillStyle = 'gray';
                                                ctx.fillText('Sem respostas registradas', width / 2, height / 2);
                                                ctx.restore();
                                            }
                                        }
                                    }]
                                };

                                new Chart(ctx, chartConfig);
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
                
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <h5 class="card-title text-center">Meu Desempenho</h5>
                        <canvas id="my" style="max-height: 200px;"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const errorsCount = @json($question->wrongCountGeneral(null, Auth::user()->id, $question->id));
                                const correctCount = @json($question->correctCountGeneral(null, Auth::user()->id, $question->id));

                                const ctx = document.querySelector('#my');

                                const chartConfig = {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Erros', 'Acertos'],
                                        datasets: [{
                                            label: 'Total',
                                            data: [errorsCount, correctCount],
                                            backgroundColor: ['#DC3545', '#198754'],
                                            hoverOffset: 4
                                        }]
                                    },
                                    options: {
                                        plugins: {
                                            tooltip: {
                                                enabled: errorsCount > 0 || correctCount > 0,
                                            }
                                        }
                                    },
                                    plugins: [{
                                        id: 'noDataPlugin',
                                        beforeDraw: (chart) => {
                                            const { datasets } = chart.data;
                                            const totalData = datasets[0].data.reduce((a, b) => a + b, 0);

                                            if (totalData === 0) {
                                                const ctx = chart.ctx;
                                                const width = chart.width;
                                                const height = chart.height;

                                                ctx.save();
                                                ctx.textAlign = 'center';
                                                ctx.textBaseline = 'middle';
                                                ctx.font = '16px Arial';
                                                ctx.fillStyle = 'gray';
                                                ctx.fillText('Sem respostas registradas', width / 2, height / 2);
                                                ctx.restore();
                                            }
                                        }
                                    }]
                                };

                                new Chart(ctx, chartConfig);
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
                </div>
            </div>
        </div>
    </div>
@endsection