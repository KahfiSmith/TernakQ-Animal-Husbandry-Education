import { Chart } from "chart.js/auto";

document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("myPieChart").getContext("2d");

    const data = {
        labels: ["Ayam Sehat", "Ayam Sakit", "Ayam Mati"],
        datasets: [
            {
                label: "Jumlah Ayam",
                data: [300, 50, 20], 
                backgroundColor: ["#56A795", "#FFC942", "#F98080"], 
                borderColor: ["#78C2AC", "#FFDA73", "#F99E9E"],
                borderWidth: 2,
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
            tooltip: {
                callbacks: {
                    label: function (context) {
                        let label = context.label || "";
                        if (label) {
                            label += ": ";
                        }
                        label += context.raw || 0;
                        return label;
                    },
                },
            },
        },
    };

    new Chart(ctx, {
        type: "pie",
        data: data,
        options: options,
    });
});
