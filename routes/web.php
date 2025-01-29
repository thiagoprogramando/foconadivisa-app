<?php

use App\Http\Controllers\Access\AccessController;
use App\Http\Controllers\Access\ForgoutController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Data\ExcelController;
use App\Http\Controllers\Data\StatisticsController;
use App\Http\Controllers\Ecommerce\EcommerceController;
use App\Http\Controllers\Ecommerce\ShopController;
use App\Http\Controllers\Gateway\AssasController;
use App\Http\Controllers\Jury\JuryController;
use App\Http\Controllers\Mkt\BannerController;
use App\Http\Controllers\Notebook\AnswerController;
use App\Http\Controllers\Notebook\NotebookController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Sale\InvoiceController;
use App\Http\Controllers\Sale\SaleController;
use App\Http\Controllers\Subject\CommentController;
use App\Http\Controllers\Subject\QuestionController;
use App\Http\Controllers\Subject\SubjectController;
use App\Http\Controllers\User\FaqController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PlanController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', [AccessController::class, 'welcome'])->name('index');
Route::get('/login', [AccessController::class, 'login'])->name('login');
Route::post('logon', [AccessController::class, 'logon'])->name('logon');

Route::get('/cadastro', [AccessController::class, 'register'])->name('cadastro');
Route::post('registrer', [AccessController::class, 'registrer'])->name('registrer');

Route::get('/recuperar-conta/{code?}', [ForgoutController::class, 'forgout'])->name('recuperar-conta');
Route::post('/send-recovery', [ForgoutController::class, 'sendRecovery'])->name('send-recovery');
Route::post('/recovery-password', [ForgoutController::class, 'recoveryPassword'])->name('recovery-password');

Route::get('/ecommerce', [EcommerceController::class, 'ecommerce'])->name('ecommerce');
Route::get('/product/{id}', [EcommerceController::class, 'product'])->name('product');
Route::get('/order/{id}', [EcommerceController::class, 'order'])->name('order');
Route::post('pay-product', [EcommerceController::class, 'payProduct'])->name('pay-product');

Route::middleware('auth')->group(function () {

    Route::middleware(['validQuestion'])->group(function () {

        Route::get('/app', [AppController::class, 'app'])->name('app');

        Route::middleware(['monthValidate'])->group(function () {

            //Search
            Route::get('search', [SearchController::class, 'search'])->name('search');

            //Notebook
            Route::get('/caderno/{id}/{tab?}', [NotebookController::class, 'notebook'])->name('caderno');
            Route::get('/caderno-filtros/{id}', [NotebookController::class, 'notebookFilter'])->name('caderno-filtros');
            Route::get('/cadernos', [NotebookController::class, 'notebooks'])->name('cadernos');
            Route::get('/completing-notebook/{id}', [NotebookController::class, 'completingNotebook'])->name('completing-notebook');
            Route::get('/criar-caderno', [NotebookController::class, 'createNotebookForm'])->name('criar-caderno');
            Route::post('create-notebook', [NotebookController::class, 'createNotebook'])->name('create-notebook');
            Route::post('add-question-notebook', [NotebookController::class, 'addQuestion'])->name('add-question-notebook');
            Route::post('update-notebook', [NotebookController::class, 'updateNotebook'])->name('update-notebook');
            Route::post('delete-notebook', [NotebookController::class, 'deleteNotebook'])->name('delete-notebook');
            Route::get('delete-notebook-get/{id}', [NotebookController::class, 'deleteGetNotebook'])->name('delete-notebook-get');

            //Ansnwer
            Route::get('/answer/{id}/{next_question?}', [AnswerController::class, 'answer'])->name('answer');
            Route::get('/answer-review/{answer}', [AnswerController::class, 'answerReview'])->name('answer-review');
            Route::post('/notebooks/{notebook}/questions/{question}/{page}/submit', [AnswerController::class, 'submitAnswerAndNext'])->name('submitAnswerAndNext');

            //Data
            Route::get('/statistic', [StatisticsController::class, 'statistic'])->name('statistic');
        });

        //User
        Route::get('/perfil', [UserController::class, 'profile'])->name('perfil');
        Route::get('/usuarios', [UserController::class, 'users'])->name('usuarios');
        Route::post('update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
        Route::post('delete-user', [UserController::class, 'deleteUser'])->name('delete-user');

        //Faq
        Route::get('faq', [FaqController::class, 'faq'])->name('faq');
        Route::get('delete-faq/{id}', [FaqController::class, 'deleteFaq'])->name('delete-faq');
        Route::post('create-faq', [FaqController::class, 'createFaq'])->name('create-faq');
        
        //Ticket
        Route::get('tickets', [FaqController::class, 'tickets'])->name('tickets');
        Route::get('delete-ticket/{id}', [FaqController::class, 'deleteTicket'])->name('delete-ticket');
        Route::post('create-ticket', [FaqController::class, 'createTicket'])->name('create-ticket');
        Route::post('update-ticket', [FaqController::class, 'updateTicket'])->name('update-ticket');

        //Notification
        Route::get('delete-notification/{id}', [NotificationController::class, 'deleteNotification'])->name('delete-notification');

        //Plan
        Route::get('/planos', [PlanController::class, 'plans'])->name('planos');
        Route::get('/plano/{id}', [PlanController::class, 'viewPlan'])->name('plano');
        Route::post('create-plan', [PlanController::class, 'createPlan'])->name('create-plan');
        Route::post('update-plan', [PlanController::class, 'updatePlan'])->name('update-plan');
        Route::post('delete-plan', [PlanController::class, 'deletePlan'])->name('delete-plan');

        //Produtcs
        Route::get('/produtos', [ProductController::class, 'products'])->name('produtos');
        Route::get('/form-create-product', [ProductController::class, 'formCreateProduct'])->name('form-create-product');
        Route::get('/form-update-product/{id}', [ProductController::class, 'formUpdateProduct'])->name('form-update-product');
        Route::post('create-product', [ProductController::class, 'createProduct'])->name('create-product');
        Route::post('update-product', [ProductController::class, 'updateProduct'])->name('update-product');
        Route::post('delete-product', [ProductController::class, 'deleteProduct'])->name('delete-product');

        //Juries
        Route::get('/bancas', [JuryController::class, 'juries'])->name('bancas');
        Route::post('create-jury', [JuryController::class, 'createJury'])->name('create-jury');
        Route::post('update-jury', [JuryController::class, 'updateJury'])->name('update-jury');
        Route::post('delete-jury', [JuryController::class, 'deleteJury'])->name('delete-jury');

        //Payment
        Route::get('/pagamentos', [PaymentController::class, 'payments'])->name('pagamentos');
        Route::post('/delete-payment', [PaymentController::class, 'deletePayment'])->name('delete-payment');

        //Sales
        Route::get('/produtos-vendas', [SaleController::class, 'sales'])->name('produtos-vendas');
        Route::get('/confirm-sale/{id}', [SaleController::class, 'confirmSale'])->name('confirm-sale');
        Route::post('delete-sale', [SaleController::class, 'deleteSale'])->name('delete-sale');

        //Shopping
        Route::get('/minhas-compras', [ShopController::class, 'shop'])->name('minhas-compras');
        
        Route::get('/vendas', [InvoiceController::class, 'invoices'])->name('vendas');
        Route::get('/confirm-invoice/{id}', [InvoiceController::class, 'confirmPayment'])->name('confirm-invoice');
        Route::post('/delete-invoice', [InvoiceController::class, 'deletePayment'])->name('delete-invoice');
        
        //Subject
        Route::get('/conteudos', [SubjectController::class, 'subjects'])->name('conteudos');
        Route::get('/conteudo/{id}', [SubjectController::class, 'viewSubject'])->name('conteudo');
        Route::post('create-subject', [SubjectController::class, 'createSubject'])->name('create-subject');
        Route::post('update-subject', [SubjectController::class, 'updateSubject'])->name('update-subject');
        Route::post('delete-subject', [SubjectController::class, 'deleteSubject'])->name('delete-subject');

        //Plan & Subject
        Route::get('/topicos', [SubjectController::class, 'topics'])->name('topicos');
        Route::post('add-subject', [PlanController::class, 'addSubject'])->name('add-subject');
        Route::post('add-topic', [PlanController::class, 'addTopic'])->name('add-topic');
        Route::post('delete-subject-associate', [PlanController::class, 'deleteSubjectAssociate'])->name('delete-subject-associate');
        Route::post('delete-topic-associate', [PlanController::class, 'deleteTopicAssociate'])->name('delete-topic-associate');

        //Plan & Gatway
        Route::get('/pay-plan/{plan}', [AssasController::class, 'payPlan'])->name('pay-plan');
        Route::post('buy-plan', [AssasController::class, 'buyPlan'])->name('buy-plan');

        // Topic
        Route::post('create-topic', [SubjectController::class, 'createTopic'])->name('create-topic');
        Route::post('delete-topic', [SubjectController::class, 'deleteTopic'])->name('delete-topic');

        //MKT
        Route::get('banners', [BannerController::class, 'banners'])->name('banners');
        Route::post('create-banner', [BannerController::class, 'createBanner'])->name('create-banner');
        Route::get('/delete-banner/{id}', [BannerController::class, 'deleteBanner'])->name('delete-banner');

        //Excel
        Route::get('/user-excel', [ExcelController::class, 'userExcel'])->name('user-excel');
        Route::get('/plan-excel', [ExcelController::class, 'planExcel'])->name('plan-excel');
        Route::get('/invoice-excel', [ExcelController::class, 'invoiceExcel'])->name('invoice-excel');
        Route::get('/sale-excel', [ExcelController::class, 'saleExcel'])->name('sale-excel');
    });

    //Question & Option
    Route::get('questao/{id}', [QuestionController::class, 'viewQuestion'])->name('questao');
    Route::get('ver-questao/{id}', [QuestionController::class, 'question'])->name('ver-questao');
    Route::get('delete-question-answer/{notebook}/{question}', [QuestionController::class, 'deleteQuestionAnswer'])->name('delete-question-answer');
    Route::get('create-question/{topic}', [QuestionController::class, 'createQuestion'])->name('create-question');
    Route::post('update-question', [QuestionController::class, 'updateQuestion'])->name('update-question');
    Route::post('delete-question', [QuestionController::class, 'deleteQuestion'])->name('delete-question');
    Route::post('create-comment', [CommentController::class, 'createComment'])->name('create-comment');
});

Route::get('/logout', [AccessController::class, 'logout'])->name('logout');