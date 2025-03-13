import { Chart } from "chart.js/auto";

// Fungsi inisialisasi chart pie
export function initPieChart() {
    // Ambil data dari global window
    const pendapatan = parseFloat(window.pendapatanBulanIni) || 0;
    const pengeluaran = parseFloat(window.pengeluaranBulanIni) || 0;

    const ctx = document.getElementById("myPieChart")?.getContext("2d");
    if (ctx) {
        new Chart(ctx, {
            type: "pie",
            data: {
                labels: ["Pendapatan", "Pengeluaran"],
                datasets: [{
                    data: [pendapatan, pengeluaran],
                    backgroundColor: [
                        "#56A795", // hijau kebiruan
                        "#F97930"  // oranye
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
            }
        });
    } else {
        console.error("Element #myPieChart tidak ditemukan.");
    }
}

// Panggil saat DOM sudah siap
document.addEventListener("DOMContentLoaded", () => {
    initPieChart();
});
