<style>
    .peta-rob {
        color: #1e293b;
        font-family: Arial, sans-serif;
    }

    .peta-rob .peta-header {
        padding: 20px 24px;
        background: #173b63;
        color: white;
        border-radius: 10px 10px 0 0;
    }

    .peta-rob .peta-header h2 {
        margin: 0 0 6px;
        font-size: 22px;
        font-weight: bold;
    }

    .peta-rob .peta-header p {
        margin: 0;
        font-size: 14px;
    }

    .peta-rob .control-panel {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 16px 24px;
        background: white;
    }

    .peta-rob .control-panel label {
        font-weight: bold;
    }

    .peta-rob .control-panel select,
    .peta-rob .control-panel button {
        padding: 9px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }

    .peta-rob .control-panel button {
        background: #173b63;
        color: white;
        cursor: pointer;
    }

    .peta-rob .control-panel button:hover {
        background: #245585;
    }

    .peta-rob #info {
        padding: 10px 24px;
        font-size: 14px;
        background: #e5e7eb;
    }

    .peta-rob .summary-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        padding: 16px 24px;
        background: #f3f4f6;
    }

    .peta-rob .summary-card {
        padding: 18px;
        border-radius: 10px;
        background: white;
        border-left: 5px solid #9ca3af;
    }

    .peta-rob .summary-card.flood {
        border-left-color: #dc5555;
    }

    .peta-rob .summary-card.safe {
        border-left-color: #36a269;
    }

    .peta-rob .summary-card.neutral {
        border-left-color: #64748b;
    }

    .peta-rob .summary-label {
        font-size: 14px;
        color: #4b5563;
    }

    .peta-rob .summary-value {
        margin-top: 8px;
        font-size: 30px;
        font-weight: bold;
        color: #173b63;
    }

    .peta-rob .summary-unit {
        font-size: 12px;
        color: #6b7280;
    }

    .peta-rob .legend {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
        padding: 14px 24px;
        background: white;
        font-size: 13px;
    }

    .peta-rob .legend-item {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .peta-rob .color-box {
        width: 15px;
        height: 15px;
        border-radius: 3px;
    }

    .peta-rob #map {
        width: 100%;
        height: 70vh;
        min-height: 450px;
        border-radius: 0 0 10px 10px;
        z-index: 1;
    }

    @media (max-width: 600px) {
        .peta-rob .summary-container {
            grid-template-columns: 1fr;
            padding: 12px 14px;
        }

        .peta-rob #map {
            height: 65vh;
        }
    }
</style>

<div class="peta-rob">

    <div class="peta-header">
        <h2>Peta Prediksi Banjir Rob</h2>
        <p>Wilayah Administrasi Jakarta Utara</p>
    </div>

    <div class="control-panel">

        <label for="tanggal">Pilih tanggal prediksi:</label>

        <select id="tanggal">
            @forelse ($tanggalPrediksi as $tanggal)
                <option value="{{ $tanggal }}">
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                </option>
            @empty
                <option value="">Belum ada hasil prediksi</option>
            @endforelse
        </select>

        <button type="button" id="btnTampilkan">
            Tampilkan
        </button>

    </div>

    <div id="info">Memuat peta...</div>

    <div class="summary-container">
        <div class="summary-card flood">
            <div class="summary-label">Terindikasi Banjir Rob</div>
            <div class="summary-value" id="totalBanjir">-</div>
            <div class="summary-unit">Kelurahan</div>
        </div>

        <div class="summary-card safe">
            <div class="summary-label">Tidak Terindikasi</div>
            <div class="summary-value" id="totalTidakBanjir">-</div>
            <div class="summary-unit">Kelurahan</div>
        </div>

        <div class="summary-card neutral">
            <div class="summary-label">Total Data Prediksi</div>
            <div class="summary-value" id="totalData">-</div>
            <div class="summary-unit">Kelurahan</div>
        </div>
    </div>

    <div class="legend">
        <div class="legend-item">
            <div class="color-box" style="background:#dc5555"></div>
            Terindikasi banjir rob
        </div>

        <div class="legend-item">
            <div class="color-box" style="background:#36a269"></div>
            Tidak terindikasi
        </div>

        <div class="legend-item">
            <div class="color-box" style="background:#9ca3af"></div>
            Belum diprediksi
        </div>
    </div>

    <div id="map"></div>

</div>

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
(function () {
    const root = document.querySelector('.peta-rob');

    if (!root || typeof L === 'undefined') {
        console.error('Elemen peta atau Leaflet tidak tersedia.');
        return;
    }

    const map = L.map(root.querySelector('#map'))
        .setView([-6.13, 106.88], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let geojsonLayer = null;
    let dataPrediksi = {};
    let modelVersi = '-';

    const endpointPrediksi = @json(route('peta.data'));

    const tanggalInput = root.querySelector('#tanggal');
    const info = root.querySelector('#info');

    function normalisasiNama(nama) {
        return String(nama || '').trim().toLowerCase();
    }

    function tanggalTerpilih() {
        return tanggalInput.value;
    }

    function warnaWilayah(feature) {
        const nama = normalisasiNama(feature.properties.kelurahan);
        const hasil = dataPrediksi[nama];

        if (!hasil) return '#9ca3af';

        return Number(hasil.is_banjir) === 1
            ? '#dc5555'
            : '#36a269';
    }

    function tampilkanPopup(feature, layer) {
        const nama = normalisasiNama(feature.properties.kelurahan);
        const hasil = dataPrediksi[nama];

        const namaKelurahan = feature.properties.kelurahan;
        const kecamatan = feature.properties.kecamatan;

        let isiPopup = `
            <div style="min-width:220px">
                <h3 style="margin:0 0 6px;color:#173b63">
                    ${namaKelurahan}
                </h3>

                <div style="font-size:12px;color:#64748b">
                    Kecamatan ${kecamatan}
                </div>

                <hr style="border:0;border-top:1px solid #ddd">
        `;

        if (hasil) {
            const terindikasi = Number(hasil.is_banjir) === 1;;
            const status = terindikasi
                ? 'Terindikasi banjir rob'
                : 'Tidak terindikasi banjir rob';

            const warna = terindikasi ? '#dc5555' : '#36a269';
            const probabilitas = (hasil.probabilitas * 100).toFixed(2);

            isiPopup += `
                <div style="
                    padding:8px;
                    background:${terindikasi ? '#fef2f2' : '#ecfdf5'};
                    color:${warna};
                    border-radius:6px;
                    font-weight:bold;
                    font-size:13px;
                ">
                    ${status}
                </div>

                <p style="margin:12px 0 4px;font-size:12px;color:#64748b">
                    Probabilitas indikasi banjir rob
                </p>

                <div style="font-size:24px;font-weight:bold;color:${warna}">
                    ${probabilitas}%
                </div>

                <div style="
                    background:#e5e7eb;
                    height:8px;
                    border-radius:10px;
                    overflow:hidden;
                    margin-top:6px;
                ">
                    <div style="
                        background:${warna};
                        width:${probabilitas}%;
                        height:8px;
                    "></div>
                </div>

                <p style="font-size:11px;color:#64748b;margin-top:12px">
                    Tanggal prediksi: ${tanggalTerpilih()}
                </p>

                <p style="font-size:11px;color:#64748b">
                    Model: ${modelVersi}
                </p>
            `;
        } else {
            isiPopup += `
                <div style="
                    padding:8px;
                    background:#f3f4f6;
                    color:#4b5563;
                    border-radius:6px;
                    font-size:13px;
                ">
                    Belum tersedia hasil prediksi.
                </div>
            `;
        }

        isiPopup += '</div>';

        layer.bindPopup(isiPopup);
    }

    function muatPrediksi() {
        const tanggal = tanggalTerpilih();

        if (!tanggal) {
            alert('Belum ada tanggal prediksi yang tersedia.');
            return;
        }

        info.textContent = 'Mengambil hasil prediksi...';

        fetch(`${endpointPrediksi}?tanggal=${encodeURIComponent(tanggal)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data prediksi.');
                }

                return response.json();
            })
            .then(response => {
                dataPrediksi = {};
                modelVersi = response.model_versi || '-';

                if (response.tersedia) {
                    response.data.forEach(item => {
                        dataPrediksi[normalisasiNama(item.nama_kelurahan)] = item;
                    });
                }

                if (geojsonLayer) {
                    geojsonLayer.setStyle(feature => ({
                        color: '#173b63',
                        weight: 1.5,
                        fillColor: warnaWilayah(feature),
                        fillOpacity: 0.65
                    }));

                    geojsonLayer.eachLayer(layer => {
                        tampilkanPopup(layer.feature, layer);
                    });
                }

                root.querySelector('#totalBanjir').textContent =
                    response.tersedia ? response.total_banjir : '0';

                root.querySelector('#totalTidakBanjir').textContent =
                    response.tersedia ? response.total_tidak_banjir : '0';

                root.querySelector('#totalData').textContent =
                    response.tersedia ? response.total_data : '0';

                info.textContent = response.tersedia
                    ? `Prediksi tanggal ${tanggal}: ${response.total_data} kelurahan berhasil dimuat.`
                    : `Belum ada hasil prediksi untuk tanggal ${tanggal}.`;
            })
            .catch(error => {
                console.error(error);
                info.textContent = 'Gagal mengambil hasil prediksi. Periksa koneksi server.';
            });
    }

    fetch('/data/kelurahan.geojson')
        .then(response => {
            if (!response.ok) {
                throw new Error('GeoJSON gagal dimuat.');
            }

            return response.json();
        })
        .then(data => {
            geojsonLayer = L.geoJSON(data, {
                style: {
                    color: '#173b63',
                    weight: 1.5,
                    fillColor: '#9ca3af',
                    fillOpacity: 0.65
                },
                onEachFeature: tampilkanPopup
            }).addTo(map);

            map.fitBounds(geojsonLayer.getBounds());

            muatPrediksi();
        })
        .catch(error => {
            console.error(error);
            info.textContent = 'Gagal memuat peta wilayah. Periksa file GeoJSON.';
        });

    root.querySelector('#btnTampilkan')
        .addEventListener('click', muatPrediksi);

    setTimeout(() => map.invalidateSize(), 200);
})();
</script>