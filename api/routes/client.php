<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\RoleController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Client\SkillController;
use App\Http\Controllers\Client\ClinicController;
use App\Http\Controllers\Client\ConfigController;
use App\Http\Controllers\Client\LineupController;
use App\Http\Controllers\Client\SourceController;
use App\Http\Controllers\Client\StatusController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\CountryController;
use App\Http\Controllers\Client\LicenseController;
use App\Http\Controllers\Client\MedicalController;
use App\Http\Controllers\Client\DocumentController;
use App\Http\Controllers\Client\IndustryController;
use App\Http\Controllers\Client\JobOrderController;
use App\Http\Controllers\Client\PositionController;
use App\Http\Controllers\Client\TrainingController;
use App\Http\Controllers\Client\ApplicantController;
use App\Http\Controllers\Client\EducationController;
use App\Http\Controllers\Client\PrincipalController;
use App\Http\Controllers\Client\ReferenceController;
use App\Http\Controllers\Client\EmploymentController;
use App\Http\Controllers\Client\ProcessingController;
use App\Http\Controllers\Client\NationalityController;
use App\Http\Controllers\Client\DocumentTypeController;
use App\Http\Controllers\Client\EmailTemplateController;
use App\Http\Controllers\Client\InterviewController;
use App\Http\Controllers\Fortify\ConfirmablePasswordController;
use App\Http\Controllers\Fortify\TwoFactorAuthenticationController;

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('client')->group(function () {
        Route::put('users/{id}/email', [UserController::class, 'updateEmail']);
        Route::put('users/{id}/password', [UserController::class, 'updatePassword']);
        Route::post('users/datatable', [UserController::class, 'datatable']);
        Route::get('users/select-option', [UserController::class, 'selectOption']);
        Route::apiResource('users', UserController::class);

        Route::apiResource('configs', ConfigController::class);

        Route::post('sources/datatable', [SourceController::class, 'datatable']);
        Route::apiResource('sources', SourceController::class);

        Route::post('status/datatable', [StatusController::class, 'datatable']);
        Route::apiResource('status', StatusController::class);

        Route::post('clinics/datatable', [ClinicController::class, 'datatable']);
        Route::apiResource('clinics', ClinicController::class);

        Route::post('document-types/datatable', [DocumentTypeController::class, 'datatable']);
        Route::apiResource('document-types', DocumentTypeController::class);

        Route::post('email-template/datatable', [EmailTemplateController::class, 'datatable']);
        Route::apiResource('email-template', EmailTemplateController::class);

        Route::get('roles/permissions', [RoleController::class, 'getPermissions']);
        Route::post('roles/update-permissions', [RoleController::class, 'updatePermissions']);
        Route::post('roles/update-permission', [RoleController::class, 'updatePermission']);
        Route::apiResource('roles', RoleController::class);

        Route::apiResource('nationality', NationalityController::class);

        // two factor authentication
        Route::post('two-factor-authentication/disable', [TwoFactorAuthenticationController::class, 'disable']);
        Route::post('two-factor-authentication/challenge', [TwoFactorAuthenticationController::class, 'challenge']);
        Route::post('two-factor-sms-authentication', [TwoFactorAuthenticationController::class, 'enableSms']);
        Route::apiResource('two-factor-authentication', TwoFactorAuthenticationController::class);
        Route::apiResource('confirm-password', ConfirmablePasswordController::class);

        Route::post('principals/datatable', [PrincipalController::class, 'datatable']);
        Route::get('principals/select-option', [PrincipalController::class, 'selectOption']);
        Route::apiResource('principals', PrincipalController::class);

        Route::get('industry/select-option', [IndustryController::class, 'selectOption']);
        Route::apiResource('industry', IndustryController::class);

        Route::get('country/select-option', [CountryController::class, 'selectOption']);
        Route::apiResource('country', CountryController::class);

        Route::post('principal-contacts/datatable', [ContactController::class, 'datatable']);
        Route::apiResource('principal-contacts', ContactController::class);

        Route::post('joborders/datatable', [JobOrderController::class, 'datatable']);
        Route::post('joborders/filter', [JobOrderController::class, 'filter']);
        Route::put('joborders/users/{id}', [JobOrderController::class, 'updateUsers']);
        Route::get('joborders/lineup/{id}', [JobOrderController::class, 'getLineup']);
        Route::get('joborders/positions', [JobOrderController::class, 'getJoborderPostions']);
        Route::apiResource('joborders', JobOrderController::class);

        Route::post('positions/datatable', [PositionController::class, 'datatable']);
        Route::put('positions/update-jobdescription/{id}', [PositionController::class, 'updateJobDescription']);
        Route::apiResource('positions', PositionController::class);

        Route::post('applicants/encode', [ApplicantController::class, 'encode']);
        Route::post('applicants/datatable', [ApplicantController::class, 'datatable']);
        Route::get('applicants/options', [ApplicantController::class, 'options']);
        Route::apiResource('applicants', ApplicantController::class);

        Route::get('educations/levels', [EducationController::class, 'levels']);
        Route::get('educations/studies', [EducationController::class, 'studies']);
        Route::apiResource('educations', EducationController::class);

        Route::apiResource('employments', EmploymentController::class);
        Route::apiResource('licenses', LicenseController::class);

        Route::get('skills/levels', [SkillController::class, 'levels']);
        Route::apiResource('skills', SkillController::class);

        Route::apiResource('trainings', TrainingController::class);
        Route::apiResource('reference', ReferenceController::class);
        Route::apiResource('document', DocumentController::class);
        Route::apiResource('medical', MedicalController::class);

        Route::post('lineup/update', [LineupController::class, 'lieupUpdate']);
        Route::apiResource('lineup', LineupController::class);
        Route::apiResource('processing', ProcessingController::class);
        Route::apiResource('interview', InterviewController::class);
    });
});