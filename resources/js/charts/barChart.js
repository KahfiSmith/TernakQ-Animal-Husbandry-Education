import { Chart } from "chart.js/auto";

document.addEventListener("DOMContentLoaded", function () {
    const data = {
        labels: ["January", "February", "March", "April", "May", "June"], 
        datasets: [
            {
                label: "Ayam Sehat",
                data: [50, 60, 70, 80, 90, 100], 
                backgroundColor: "#F97930", 
                borderColor: "#F97930", 
                borderWidth: 1,
            },
            {
                label: "Ayam Sakit",
                data: [10, 15, 20, 25, 30, 35], 
                backgroundColor: "#8BBDB2", 
                borderColor: "#8BBDB2", 
                borderWidth: 1,
            },
            {
                label: "Ayam Mati",
                data: [23, 56, 32, 21, 10, 10], 
                backgroundColor: "#FFC942", 
                borderColor: "#FFC942", 
                borderWidth: 1,
            },
        ],
    };

    // Konfigurasi chart
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
