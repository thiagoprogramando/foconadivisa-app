@extends('app.layout')
@section('title') Estátisticas @endsection
@section('content')

<div class="col-12 card">
    <div class="card-header">
        <h3>Desempenho Geral</h3>
    </div>
    <div class="card-body row">

        <div class="col-4 col-sm-12 col-md-6 col-lg-6 mt-3 row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title text-center"><span>Questões resolvidas</span></h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="ps-3">
                                <h6>{{ $answers->count() }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title text-center"><span>Acertos</span></h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="ps-3">
                                <h6 class="text-success">{{ $answersCorrect->count() }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title text-center"><span>Total de Matérias</span></h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="ps-3">
                                <h6>{{ $subjects->count() }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title text-center"><span>Erros</span></h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="ps-3">
                                <h6 class="text-danger">{{ $answersInCorrect->count() }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4 col-sm-12 col-md-6 col-lg-6 mt-3">
            <div id="trafficChart" style="min-height: 300px;" class="echart"></div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {

                    const errorsCount = @json($answersInCorrect->count());
                    const correctCount = @json($answersCorrect->count());

                    echarts.init(document.querySelector("#trafficChart")).setOption({
                        tooltip: {
                        trigger: 'item'
                        },
                        legend: {
                        top: '5%',
                        left: 'center'
                        },
                        series: [{
                            name: 'Acertos X Erros',
                            type: 'pie',
                            radius: ['40%', '70%'],
                            avoidLabelOverlap: false,
                            label: {
                                show: false,
                                position: 'center'
                            },
                            emphasis: {
                                label: {
                                show: true,
                                fontSize: '18',
                                fontWeight: 'bold'
                                }
                            },
                            labelLine: {
                                show: false
                            },
                            data: [{
                                value: correctCount,
                                name: 'Acertos',
                                itemStyle: {
                                        color: '#28a745'
                                    }
                            },
                            {
                            value: errorsCount,
                            name: 'Erros',
                            itemStyle: {
                                        color: '#dc3545'
                                    }
                            },]
                        }]
                    });
                });
            </script>
        </div>

        <div class="col-4 col-sm-12 col-md-12 col-lg-12 mt-3">
            <hr>
            <canvas id="radarChart" style="max-height: 400px;"></canvas>
        
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    // Dados que você vai usar no gráfico
                    const subjects = @json($subjects); // Passando os subjects para o JavaScript

                    // Coletar os dados de acertos e erros para cada subject
                    const labels = subjects.map(subject => subject.name);  // Nomes dos subjects
                    console.log(labels);
                    const correctAnswers = subjects.map(subject => subject.answers_correct);  // Contagem de respostas corretas
                    const incorrectAnswers = subjects.map(subject => subject.answers_incorrect);  // Contagem de respostas incorretas

                    // Criando o gráfico
                    new Chart(document.querySelector('#radarChart'), {
                    type: 'radar',
                    data: {
                        labels: labels,  // Nomes dos subjects
                        datasets: [{
                        label: 'Acertos',
                        data: correctAnswers,  // Dados de acertos
                        fill: true,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgb(75, 192, 192)',
                        pointBackgroundColor: 'rgb(75, 192, 192)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(75, 192, 192)'
                        }, {
                        label: 'Erros',
                        data: incorrectAnswers,  // Dados de erros
                        fill: true,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgb(255, 99, 132)',
                        pointBackgroundColor: 'rgb(255, 99, 132)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(255, 99, 132)'
                        }]
                    },
                    options: {
                        elements: {
                        line: {
                            borderWidth: 3
                        }
                        },
                        scales: {
                        r: {
                            min: 0,  // Define o valor mínimo do gráfico (pode ajustar conforme necessário)
                            max: Math.max(...correctAnswers.concat(incorrectAnswers))  // Definir o valor máximo baseado nas respostas
                        }
                        }
                    }
                    });
                });
            </script> 
        </div>        
    </div>
</div>
@endsection