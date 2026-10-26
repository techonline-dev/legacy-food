<?php

namespace App\Controllers;

use App\Core\AdminProvisioner;
use App\Core\Controller;
use App\Core\Database;

class InstallController extends Controller {
    public function index(): void {
        $this->guardAccess();

        $status = [];
        $connected = false;
        $tablesCount = 0;

        try {
            $pdo = Database::getInstance();
            $connected = true;
            $status[] = ['type' => 'success', 'msg' => 'Connected to MySQL server successfully.'];

            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            $tablesCount = count($tables);

            if ($tablesCount > 0) {
                $status[] = ['type' => 'info', 'msg' => "Found {$tablesCount} existing tables in database."];
            }
        } catch (\Throwable $e) {
            error_log('Installer database check failed: ' . $e->getMessage());
            $status[] = ['type' => 'error', 'msg' => 'Database connection failed. Verify the server and .env credentials.'];
        }

        $this->view('install.index', [
            'meta_title' => 'Legacy Food — System Setup & Diagnostics',
            'connected' => $connected,
            'tablesCount' => $tablesCount,
            'status' => $status,
            'requiresInstallKey' => $this->requiresKey(),
        ], null);
    }

    public function run(): void {
        $this->guardAccess();

        try {
            Database::autoMigrate();

            if ((string)env('ADMIN_EMAIL', '') !== '' || (string)env('ADMIN_PASSWORD', '') !== '') {
                AdminProvisioner::fromEnv(true);
            }

            flash('success', 'Database schema and initial Legacy Food seed data installed successfully.');
        } catch (\Throwable $e) {
            error_log('Installer run failed: ' . $e->getMessage());
            flash('error', 'Installation failed. Check the server error log for details.');
        }

        $this->redirect('install');
    }

    private function requiresKey(): bool {
        return config('app.env') === 'production' || (string)env('INSTALL_KEY', '') !== '';
    }

    private function guardAccess(): void {
        if (!$this->requiresKey()) {
            return;
        }

        $expected = (string)env('INSTALL_KEY', '');
        $provided = (string)$this->request->input('install_key', '');

        if ($expected === '' || !hash_equals($expected, $provided)) {
            $this->response->status(404);
            $this->view('errors.404', ['title' => 'Page Not Found'], 'main');
            exit;
        }
    }
}
