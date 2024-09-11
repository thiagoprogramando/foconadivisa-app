<?php

use App\Http\Controllers\Access\AccessController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Gateway\AssasController;
use App\Http\Controllers\Notebook\AnswerController;
use App\Http\Controllers\Notebook\NotebookController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Subject\QuestionController;
use App\Http\Controllers\Subject\SubjectController;

use App\Http\Controllers\User\PlanController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AccessController::class, 'login'])->name('index');
Route::get('/login', [AccessController::class, 'login'])->name('login');
Route::post('logon', [AccessController::class, 'logon'])->name('logon');

Route::get('/cadastro', [AccessController::class, 'register'])->name('cadastro');
Route::post('registrer', [AccessController::class, 'registrer'])->name('registrer');

Route::middleware('auth')->group(function () {

    Route::get('/app', [AppController::class, 'app'])->name('app');

    //User
    Route::get('/perfil', [UserController::class, 'profile'])->name('perfil');
    Route::get('/usuarios', [UserController::class, 'users'])->name('usuarios');
    Route::post('update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::post('delete-user', [UserController::class, 'deleteUser'])->name('delete-user');

    //Plan
    Route::get('/planos', [PlanController::class, 'plans'])->name('planos');
    Route::get('/plano/{id}', [PlanController::class, 'viewPlan'])->name('plano');
    Route::post('create-plan', [PlanController::class, 'createPlan'])->name('create-plan');
    Route::post('update-plan', [PlanController::class, 'updatePlan'])->name('update-plan');
    Route::post('delete-plan', [PlanController::class, 'deletePlan'])->name('delete-plan');

    //Payment
    Route::get('/pagamentos', [PaymentController::class, 'payments'])->name('pagamentos');
    Route::post('/delete-payment', [PaymentController::class, 'deletePayment'])->name('delete-payment');
    
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

    // Topic
    Route::post('create-topic', [SubjectController::class, 'createTopic'])->name('create-topic');
    Route::post('delete-topic', [SubjectController::class, 'deleteTopic'])->name('delete-topic');

    //Question & Option
    Route::get('questao/{id}', [QuestionController::class, 'viewQuestion'])->name('questao');
    Route::get('create-question/{subject}', [QuestionController::class, 'createQuestion'])->name('create-question');
    Route::post('update-question', [QuestionController::class, 'updateQuestion'])->name('update-question');
    Route::post('delete-question', [QuestionController::class, 'deleteQuestion'])->name('delete-question');
    Route::get('delete-question-answer/{notebook}/{question}', [QuestionController::class, 'deleteQuestionAnswer'])->name('delete-question-answer');

    //Notebook
    Route::get('/caderno/{id}', [NotebookController::class, 'notebook'])->name('caderno');
    Route::get('/cadernos', [NotebookController::class, 'notebooks'])->name('cadernos');
    Route::get('/completing-notebook/{id}', [NotebookController::class, 'completingNotebook'])->name('completing-notebook');
    Route::post('create-notebook', [NotebookController::class, 'createNotebook'])->name('create-notebook');
    Route::post('delete-notebook', [NotebookController::class, 'deleteNotebook'])->name('delete-notebook');
    Route::post('update-notebook', [NotebookController::class, 'updateNotebook'])->name('update-notebook');

    //Ansnwer
    Route::get('/answer/{id}', [AnswerController::class, 'answer'])->name('answer');
    Route::get('/answer-review/{answer}', [AnswerController::class, 'answerReview'])->name('answer-review');
    Route::post('/notebooks/{notebook}/questions/{question}/{page}/submit', [AnswerController::class, 'submitAnswerAndNext'])->name('submitAnswerAndNext');
    
});

Route::get('/logout', [AccessController::class, 'logout'])->name('logout');