<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="balanceChart"></canvas>

<script>
new Chart(document.getElementById("balanceChart"), {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{
            label: 'Balance Trend',
            data: [150000,160000,170000,165000,180000,200000,200000],
            borderWidth: 2,
            tension: 0.4
        }]
    }
});
</script>
