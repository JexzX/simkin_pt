<?php

namespace Config;

class Validation
{
    public $ruleSets = [
        \CodeIgniter\Validation\Rules::class,
        \CodeIgniter\Validation\FormatRules::class,
        \CodeIgniter\Validation\FileRules::class,
        \CodeIgniter\Validation\CreditCardRules::class,
    ];

    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // Validation rules for SKP
    public $skp = [
        'periode_id' => 'required|numeric',
    ];

    public $skp_errors = [
        'periode_id' => [
            'required' => 'Periode harus dipilih',
            'numeric'  => 'Periode tidak valid',
        ],
    ];

    // Validation rules for RHK
    public $rhk = [
        'skp_id'         => 'required|numeric',
        'nama_rhk'       => 'required|min_length[3]',
        'jenis_rhk'      => 'required|in_list[kuantitatif,kualitatif]',
        'klasifikasi'    => 'required|in_list[utama,tambahan]',
    ];

    // Validation rules for Realisasi
    public $realisasi = [
        'rhk_indikator_id' => 'required|numeric',
        'bulan'            => 'required|numeric|greater_than[0]|less_than[13]',
        'realisasi_kuantitas' => 'permit_empty|numeric',
    ];
}