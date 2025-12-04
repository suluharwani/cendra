<?php

if (!function_exists('generate_project_code')) {
    function generate_project_code($prefix = 'PRJ')
    {
        $year = date('y');
        $month = date('m');
        $random = strtoupper(bin2hex(random_bytes(3)));
        
        return $prefix . '-' . $year . $month . '-' . $random;
    }
}

if (!function_exists('generate_subscription_code')) {
    function generate_subscription_code()
    {
        return 'SUB-' . strtoupper(bin2hex(random_bytes(4)));
    }
}

if (!function_exists('generate_quotation_number')) {
    function generate_quotation_number()
    {
        $year = date('Y');
        $month = date('m');
        $db = \Config\Database::connect();
        $count = $db->table('quotations')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->countAllResults();
        
        $seq = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return 'QUO-' . $year . '-' . $month . '-' . $seq;
    }
}

if (!function_exists('generate_invoice_number')) {
    function generate_invoice_number()
    {
        $year = date('Y');
        $month = date('m');
        $db = \Config\Database::connect();
        $count = $db->table('subscription_invoices')
            ->where('YEAR(created_at)', $year)
            ->where('MONTH(created_at)', $month)
            ->countAllResults();
        
        $seq = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return 'INV-' . $year . '-' . $month . '-' . $seq;
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('calculate_tax')) {
    function calculate_tax($amount, $tax_rate = 11)
    {
        return ($amount * $tax_rate) / 100;
    }
}

if (!function_exists('get_project_status_badge')) {
    function get_project_status_badge($status)
    {
        $badges = [
            'quotation' => '<span class="badge bg-secondary">Quotation</span>',
            'negotiation' => '<span class="badge bg-info">Negotiation</span>',
            'confirmed' => '<span class="badge bg-primary">Confirmed</span>',
            'in_progress' => '<span class="badge bg-warning">In Progress</span>',
            'testing' => '<span class="badge bg-info">Testing</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'delivered' => '<span class="badge bg-success">Delivered</span>',
            'on_hold' => '<span class="badge bg-secondary">On Hold</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>'
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">' . $status . '</span>';
    }
}

if (!function_exists('get_subscription_status_badge')) {
    function get_subscription_status_badge($status)
    {
        $badges = [
            'active' => '<span class="badge bg-success">Active</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'suspended' => '<span class="badge bg-secondary">Suspended</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            'expired' => '<span class="badge bg-secondary">Expired</span>',
            'completed' => '<span class="badge bg-info">Completed</span>'
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">' . $status . '</span>';
    }
}

if (!function_exists('send_notification')) {
    function send_notification($user_id, $type, $title, $message, $data = null)
    {
        $db = \Config\Database::connect();
        
        return $db->table('notifications')->insert([
            'user_id' => $user_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data ? json_encode($data) : null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}