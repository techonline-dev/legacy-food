<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Testimonial;

class AdminTestimonialController extends Controller {
    public function index(): void {
        $testimonials = Testimonial::all('sort_order ASC');
        $editId = (int)($this->request->query('edit') ?? 0);
        $editTestimonial = $editId > 0 ? Testimonial::find($editId) : null;

        $this->view('admin.testimonials.index', [
            'meta_title' => 'Testimonials Management | Legacy Food Admin',
            'testimonials' => $testimonials,
            'editTestimonial' => $editTestimonial
        ], 'admin');
    }

    public function edit(int $id): void {
        $testimonials = Testimonial::all('sort_order ASC');
        $editTestimonial = Testimonial::find($id);

        if (!$editTestimonial) {
            flash('error', 'Testimonial not found.');
            $this->redirect('admin/testimonials');
            return;
        }

        $this->view('admin.testimonials.index', [
            'meta_title' => 'Edit Testimonial | Legacy Food Admin',
            'testimonials' => $testimonials,
            'editTestimonial' => $editTestimonial
        ], 'admin');
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/testimonials');
            return;
        }

        $name = sanitize($this->request->input('customer_name'));
        $location = sanitize($this->request->input('location'));
        $rating = (int)$this->request->input('rating', 5);
        $review = sanitize($this->request->input('review'));

        Database::insert('testimonials', [
            'customer_name' => $name,
            'location' => $location,
            'rating' => $rating,
            'review' => $review,
            'is_active' => 1
        ]);

        flash('success', 'Testimonial added.');
        $this->redirect('admin/testimonials');
    }

    public function update(int $id): void {
        if (!$this->validateCsrf()) {
            flash('error', 'Token expired.');
            $this->redirect('admin/testimonials');
            return;
        }

        $t = Testimonial::find($id);
        if (!$t) {
            flash('error', 'Testimonial not found.');
            $this->redirect('admin/testimonials');
            return;
        }

        $name = sanitize($this->request->input('customer_name'));
        $location = sanitize($this->request->input('location'));
        $rating = (int)$this->request->input('rating', 5);
        $review = sanitize($this->request->input('review'));
        $isActive = (int)$this->request->input('is_active', 1);

        Database::update('testimonials', [
            'customer_name' => $name,
            'location' => $location,
            'rating' => $rating,
            'review' => $review,
            'is_active' => $isActive
        ], '`id` = :id', ['id' => $id]);

        flash('success', 'Testimonial updated.');
        $this->redirect('admin/testimonials');
    }

    public function delete(int $id): void {
        Database::query("DELETE FROM `testimonials` WHERE `id` = :id", ['id' => $id]);
        flash('success', 'Testimonial deleted.');
        $this->redirect('admin/testimonials');
    }
}
