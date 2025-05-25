<script src="assets/js/script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pie Chart
        const pieCtx = document.getElementById('pieChart')?.getContext('2d');
        if (pieCtx) {
            fetch('api/get_kriteria.php')
                .then(response => response.json())
                .then(data => {
                    new Chart(pieCtx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.weights,
                                backgroundColor: [
                                    '#3B82F6', '#10B981', '#F59E0B',
                                    '#EF4444', '#8B5CF6'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'right'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                });
        }

        // Bar Chart - hanya render jika ada data
        const barCtx = document.getElementById('barChart')?.getContext('2d');
        if (barCtx) {
            <?php
            $query_alt = "SELECT a.nama, h.nilai_v 
                     FROM alternatif a
                     JOIN hasil h ON a.id = h.id_alternatif
                     ORDER BY h.ranking";
            $result_alt = mysqli_query($koneksi, $query_alt);
            $labels = [];
            $data = [];

            while ($row = mysqli_fetch_assoc($result_alt)) {
                $labels[] = "'" . addslashes($row['nama']) . "'";
                $data[] = $row['nilai_v'];
            }
            ?>

            const barData = {
                labels: [<?= implode(',', $labels) ?>],
                datasets: [{
                    label: 'Nilai V',
                    data: [<?= implode(',', $data) ?>],
                    backgroundColor: '#3B82F6',
                    borderWidth: 1
                }]
            };

            new Chart(barCtx, {
                type: 'bar',
                data: barData,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 0.3
                        }
                    }
                }
            });
        }
    });
</script>
</body>

</html>