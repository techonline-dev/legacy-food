<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class InstallController extends Controller {
    public function index(): void {
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
            $status[] = ['type' => 'error', 'msg' => 'Database connection failed: ' . $e->getMessage()];
        }

        $this->view('install.index', [
            'meta_title' => 'Legacy Food — System Setup & Diagnostics',
            'connected' => $connected,
            'tablesCount' => $tablesCount,
            'status' => $status
        ], null);
    }

    public function run(): void {
        try {
            Database::autoMigrate();
            flash('success', 'Database schema and initial Legacy Food seed data installed successfully!');
        } catch (\Throwable $e) {
            flash('error', 'Installation error: ' . $e->getMessage());
        }

        $this->redirect('install');
    }
}
