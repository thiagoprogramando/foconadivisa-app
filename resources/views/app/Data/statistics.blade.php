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
            <div id="budgetChart" style="min-height: 400px;" class="echart"></div>
        
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    
                    const subjects = @json($subjects->pluck('name')); 
                    const subjectCounts = @json($subjects->pluck('answers_count'));
        
                    var indicators = subjects.map((subject) => {
                        return { name: subject, max: Math.max(...subjectCounts) };
                    });
        
                    var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                        legend: {
                            data: ['Conteúdos']
                        },
                        radar: {
                            shape: 'circle',
                            indicator: indicators
                        },
                        series: [{
                            name: 'Conteúdos',
                            type: 'radar',
                            data: [{
                                value: subjectCounts,
                                name: 'Conteúdos'
                            }]
                        }]
                    });
                });
            </script>
        </div>        
    </div>
</div>
@endsection