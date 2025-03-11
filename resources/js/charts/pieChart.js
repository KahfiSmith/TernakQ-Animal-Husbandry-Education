import { Chart } from "chart.js/auto";

document.addEventListener("DOMContentLoaded", () => {
    // Ambil data dari global window
    const pendapatan = parseFloat(window.pendapatanBulanIni) || 0;
    const pengeluaran = parseFloat(window.pengeluaranBulanIni) || 0;

    const ctx = document.getElementById("myPieChart").getContext("2d");
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
});
