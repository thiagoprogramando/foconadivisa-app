@extends('app.layout')
@section('title') Dashboard @endsection
@section('content')

    <div class="col-sm-12 col-md-7 col-lg-7 mb-3">
        <div class="row">
            <div class="col-6 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('template/img/components/monitoring.png') }}" class="img-fluid w-50" alt="Trabalhando...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Olá, {{ Auth::user()->name }}!</h5>
                                <p class="card-text">
                                    O seu plano atual é: <a href="{{ route('planos') }}">{{ Auth::user()->labelPlan->name }}</a> <br>
                                    Aproveite os benefícios da sua assinatura.
                                </p>                                                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Questões <span>| Hoje</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="bi bi-question-square-fill"></i> </div>
                                    <div class="ps-3">
                                        <h6>145</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Questões <span>| Geral</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="bi bi-book-half"></i> </div>
                                    <div class="ps-3">
                                        <h6>145</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Progresso</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="bi bi-bar-chart-fill"></i> </div>
                                    <div class="ps-3">
                                        <h6>145</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-5 col-lg-5">
        <div class="card">
            <div class="card-body pb-0">
                <h5 class="card-title">Novidades &amp; Atualizações <span>| Recentes</span></h5>
                <div class="news">
                    <div class="post-item clearfix">
                        <img src="assets/img/news-1.jpg" alt="">
                        <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                        <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                    </div>

                    <div class="post-item clearfix">
                        <img src="assets/img/news-2.jpg" alt="">
                        <h4><a href="#">Quidem autem et impedit</a></h4>
                        <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
                    </div>

                    <div class="post-item clearfix">
                        <img src="assets/img/news-3.jpg" alt="">
                        <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                        <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Relatório de respostas</h5>
                      <canvas id="doughnutChart" style="max-height: 400px;"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#doughnutChart'), {
                                    type: 'doughnut',
                                    data: {
                                        labels: [
                                            'Erros',
                                            'Sem respostas',
                                            'Acertos'
                                        ],
                                        datasets: [{
                                            label: 'Avanço de respostas',
                                            data: [300, 50, 100],
                                            backgroundColor: [
                                            'rgb(255, 99, 132)',
                                            'rgb(54, 162, 235)',
                                            'rgb(153, 204, 50)'
                                            ],
                                            hoverOffset: 4
                                        }]
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Continue de onde parou...</h5>
                        
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Caderno</th>
                                    <th scope="col">Conteúdo</th>
                                    <th scope="col" class="text-center">Questões</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#545 caderno</td>
                                    <td>
                                        <span class="badge bg-primary">Matemática Financeira</span> 
                                        <span class="badge bg-primary">Português</span> 
                                        <span class="badge bg-primary">Geografia</span>
                                    </td>
                                    <td class="text-center">28</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection