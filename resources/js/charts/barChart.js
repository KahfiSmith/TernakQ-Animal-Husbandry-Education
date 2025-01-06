import { Chart } from "chart.js/auto";

document.addEventListener("DOMContentLoaded", function () {
    const data = {
        labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
        datasets: [
            {
                label: "Ayam Sehat",
                data: [50, 32, 65, 12, 45, 45, 50, 60, 32, 34, 76, 32], 
                backgroundColor: "#56A795", 
                borderColor: "#78C2AC", 
                borderWidth: 1,
            },
            {
                label: "Ayam Sakit",
                data: [10, 15, 20, 25, 30, 35, 12, 32, 65, 80, 43, 12], 
                backgroundColor: "#FFC942", 
                borderColor: "#FFDA73", 
                borderWidth: 1,
            },
            {
                label: "Ayam Mati",
                data: [23, 56, 32, 21, 10, 10, 54, 60, 70, 43, 32, 54], 
                backgroundColor: "#F97930", 
                borderColor: "#F9B37A", 
                borderWidth: 1,
            },
        ],
    };

    const options = {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: "top",
            },
        },
        scales: {
            x: {
                grid: {
                    display: false, 
                },
            },
            y: {
                grid: {
                    color: "#e2e8f0", 
                },
                beginAtZero: true, 
            },
        },
    };

    const ctx = document.getElementById("myBarChart").getContext("2d");
    new Chart(ctx, {
        type: "bar", 
        data: data,
        options: options,
    });
});
