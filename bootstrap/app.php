<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Console\Scheduling\Schedule;

use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\CheckRegistrationWindow;
use App\Http\Middleware\CheckApplicationWindow;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        // $schedule->command('fetch:proposal')->everyMinute();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => AdminMiddleware::class,
            'registrationWindow' => CheckRegistrationWindow::class,
            'applicationWindow' => CheckApplicationWindow::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
    