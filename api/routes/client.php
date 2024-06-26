<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\RoleController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Client\SkillController;
use App\Http\Controllers\Client\ClinicController;
use App\Http\Controllers\Client\ConfigController;
use App\Http\Controllers\Client\LineupController;
use App\Http\Controllers\Client\ReportController;
use App\Http\Controllers\Client\ResumeController;
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
use App\Http\Controllers\Client\InterviewController;
use App\Http\Controllers\Client\PrincipalController;
use App\Http\Controllers\Client\ReferenceController;
use App\Http\Controllers\Client\EmploymentController;
use App\Http\Controllers\Client\MonitoringController;
use App\Http\Controllers\Client\ProcessingController;
use App\Http\Controllers\Client\NationalityController;
use App\Http\Controllers\Client\DocumentTypeController;
use App\Http\Controllers\Client\EmailTemplateController;
use App\Http\Controllers\Fortify\ConfirmablePasswordController;
use App\Http\Controllers\Fortify\TwoFactorAuthenticationController;

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('client')->group(function () {
        Route::put('users/{id}/email', [UserController::class, 'updateEmail']);
        Route::put('users/{id}/password', [UserController::class, 'updatePassword']);
        Route::put('users/{id}/backup', [UserController::class, 'updateBackup']);
        Route::post('users/datatable', [UserController::class, 'datatable']);
        Route::get('users/select-option', [UserController::class, 'selectOption']);
        Route::get('activity-logs', [UserController::class, 'activityLogs']);
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
        Route::post('email-template/send', [EmailTemplateController::class, 'sendEmail']);
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

        Route::get('positions/joborder/{id}', [PositionController::class, 'joborderpositions']);
        Route::post('positions/datatable', [PositionController::class, 'datatable']);
        Route::put('positions/update-jobdescription/{id}', [PositionController::class, 'updateJobDescription']);
        Route::apiResource('positions', PositionController::class);

        Route::post('applicants/encode', [ApplicantController::class, 'encode']);
        Route::post('applicants/datatable', [ApplicantController::class, 'datatable']);
        Route::post('applicants/resume-parser', [ApplicantController::class, 'resumeParser']);
        Route::post('applicants/trashed', [ApplicantController::class, 'getTrashed']);
        Route::post('applicants/store-resume', [ApplicantController::class, 'storeResume']);
        Route::post('applicants/upload-photo', [ApplicantController::class, 'uploadPhoto']);
        Route::get('applicants/options', [ApplicantController::class, 'options']);
        Route::get('applicants/get-resume', [ApplicantController::class, 'getResumeData']);
        Route::get('applicants/{id}/return', [ApplicantController::class, 'returnApplicant']);
        Route::delete('applicants/delete-resume', [ApplicantController::class, 'deleteResume']);
        Route::delete('applicants/{id}/permanent', [ApplicantController::class, 'permanentDelete']);
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

        // monitoring route
        Route::prefix('process')->group(function () {
            Route::post('applicants/datatable', [MonitoringController::class, 'applicants']);
            Route::post('documents/datatable', [MonitoringController::class, 'documents']);
        });

        // reports route
        Route::prefix('reports')->group(function () {
            Route::post('applicant-source', [ReportController::class, 'generateApplicantSource']);
            Route::post('documents-datatable', [ReportController::class, 'generateDocuments']);
            Route::post('applicant-encoded', [ReportController::class, 'generateEncodedApplicant']);
            Route::post('applicant-sources', [ReportController::class, 'generateSourceApplicant']);
            Route::post('applicant-status', [ReportController::class, 'generateStatusApplicant']);
            Route::post('audit-trail', [ReportController::class, 'generateAuditTrail']);
            Route::post('applicant-birthdate', [ReportController::class, 'generateBirthdateReport']);
            Route::post('deployment', [ReportController::class, 'generateDeployment']);
            Route::post('interview', [ReportController::class, 'generateInterview']);
            Route::post('manpower', [ReportController::class, 'generateManpower']);

            Route::post('export/applicant-encoded', [ReportController::class, 'exportEncodedApplicant']);
            Route::post('export/applicant-source', [ReportController::class, 'exportApplicantSource']);
            Route::post('export/audit-trail', [ReportController::class, 'exportActivityLog']);
            Route::post('export/birthday', [ReportController::class, 'exportBirthday']);
            Route::post('export/deployment', [ReportController::class, 'exportDeployment']);
            Route::post('export/interview', [ReportController::class, 'exportInterview']);
            Route::post('export/manpower', [ReportController::class, 'exportManpower']);
            Route::post('export/applicant-status', [ReportController::class, 'exportStatus']);
        });

        Route::apiResource('resume-parser', ResumeController::class);
    });
});