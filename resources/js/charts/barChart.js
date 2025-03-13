import { Chart } from 'chart.js/auto';

// Fungsi inisialisasi chart batang
export function initBarChart() {
    // Ambil data yang diset di Blade
    const monthlyData = window.monthlyData || [];
    
    // Array nama bulan lengkap
    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    // Inisialisasi data untuk tiap bulan dengan nilai default 0
    const sickByMonth = new Array(12).fill(0);
    const deadByMonth = new Array(12).fill(0);

    // Isi data berdasarkan bulan (asumsi item.month adalah angka 1-12)
    monthlyData.forEach(item => {
        // Pastikan indeksnya benar (bulan 1 => index 0, dst.)
        sickByMonth[item.month - 1] = item.sick;
        deadByMonth[item.month - 1] = item.dead;
    });

    // Gunakan nama bulan sebagai label dan array data yang sudah lengkap
    const data = {
        labels: monthNames,
        datasets: [
            {
                label: "Ayam Sakit",
                data: sickByMonth,
                backgroundColor: "#FFC942",
            },
            {
                label: "Ayam Mati",
                data: deadByMonth,
                backgroundColor: "#F97930",
            }
        ]
    };

    const ctx = document.getElementById("myBarChart")?.getContext("2d");
    if (ctx) {
        new Chart(ctx, {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    } else {
        console.error("Element #myBarChart tidak ditemukan.");
    }
}

// Panggil saat DOM sudah siap
document.addEventListener("DOMContentLoaded", function () {
    initBarChart();
});
