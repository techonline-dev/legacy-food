<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Faq;

class AdminFaqController extends Controller {
    public function index(): void {
        $faqs = Faq::all('sort_order ASC');
        $editId = (int)($this->request->query('edit') ?? 0);
        $editFaq = $editId > 0 ? Faq::find($editId) : null;

        $this->view('admin.faqs.index', [
            'meta_title' => 'FAQ Management | Legacy Food Admin',
            'faqs' => $faqs,
            'editFaq' => $editFaq
        ], 'admin');
    }

    public function edit(int $id): void {
        $faqs = Faq::all('sort_order ASC');
        $editFaq = Faq::find($id);

        if (!$editFaq) {
            flash('error', 'FAQ not found.');
            $this->redirect('admin/faqs');
            return;
        }

        $this->view('admin.faqs.index', [
            'meta_title' => 'Edit FAQ | Legacy Food Admin',
            'faqs' => $faqs,
            'editFaq' => $editFaq
        ], 'admin');
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/faqs');
            return;
        }

        $category = sanitize($this->request->input('category', 'General'));
        $question = sanitize($this->request->input('question'));
        $answer = $this->request->input('answer');
        $order = (int)$this->request->input('sort_order', 0);

        Database::insert('faqs', [
            'category' => $category,
            'question' => $question,
            'answer' => $answer,
            'sort_order' => $order,
            'is_active' => 1
        ]);

        flash('success', 'FAQ added.');
        $this->redirect('admin/faqs');
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/faqs');
            return;
        }

        $faq = Faq::find($id);
        if (!$faq) {
            flash('error', 'FAQ not found.');
            $this->redirect('admin/faqs');
            return;
        }

        $category = sanitize($this->request->input('category', 'General'));
        $question = sanitize($this->request->input('question'));
        $answer = $this->request->input('answer');
        $order = (int)$this->request->input('sort_order', 0);
        $isActive = (int)$this->request->input('is_active', 1);

        Database::update('faqs', [
            'category' => $category,
            'question' => $question,
            'answer' => $answer,
            'sort_order' => $order,
            'is_active' => $isActive
        ], '`id` = :id', ['id' => $id]);

        flash('success', 'FAQ updated.');
        $this->redirect('admin/faqs');
    }

    public function delete(int $id): void {
        Database::query("DELETE FROM `faqs` WHERE `id` = :id", ['id' => $id]);
        flash('success', 'FAQ deleted.');
        $this->redirect('admin/faqs');
    }
}
