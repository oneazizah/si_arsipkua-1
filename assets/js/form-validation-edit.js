document.addEventListener("DOMContentLoaded", function () {

    const currentId = new URLSearchParams(window.location.search).get("id");

    function showError(title, message) {
        Swal.fire({
            icon: "error",
            title: title,
            text: message,
            background: document.body.classList.contains("dark-mode") ? "#1f2937" : "#ffffff",
            color: document.body.classList.contains("dark-mode") ? "#ffffff" : "#000000",
        });
    }

    /* ============================
       VALIDASI NOMOR AKTA (EDIT)
       ============================ */
    const nomorAktaInput = document.querySelector("input[name='nomor_akta']");

    nomorAktaInput.addEventListener("input", () => {
        let nilai = nomorAktaInput.value;

        nomorAktaInput.value = nilai
            .toUpperCase()
            .replace(/[^A-Z0-9\-\/]/g, "")
            .replace(/\s+/g, "");

        if (nomorAktaInput.value.length < 5) return;

        fetch(`validate_akta.php?nomor=${encodeURIComponent(nomorAktaInput.value)}&id=${currentId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "duplicate") {
                    showError("Nomor Akta Duplikat", "Nomor Akta ini sudah digunakan arsip lain.");
                }
            });
    });


    /* ============================
       VALIDASI NIK PRIA / WANITA
       ============================ */
    const nikPriaInput = document.querySelector("input[name='nik_pria']");
    const nikWanitaInput = document.querySelector("input[name='nik_wanita']");

    function validateNIK(value) {
        return /^[0-9]{16}$/.test(value);
    }

    function checkNikDuplicate(type, nik) {
        if (nik.length !== 16) return;

        fetch(`validate_nik.php?type=${type}&nik=${nik}&id=${currentId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "duplicate") {
                    showError("NIK Sudah Terdaftar", `NIK mempelai ${type} sudah digunakan pada arsip lain.`);
                }
            });
    }

    nikPriaInput.addEventListener("input", () => {
        nikPriaInput.value = nikPriaInput.value.replace(/\D/g, "").slice(0, 16);

        if (nikPriaInput.value.length === 16 && !validateNIK(nikPriaInput.value)) {
            showError("Format NIK Salah", "NIK pria harus 16 digit angka.");
        }

        if (nikPriaInput.value === nikWanitaInput.value && nikPriaInput.value.length === 16) {
            showError("NIK Tidak Valid", "NIK pria dan wanita tidak boleh sama.");
        }

        checkNikDuplicate("pria", nikPriaInput.value);
    });

    nikWanitaInput.addEventListener("input", () => {
        nikWanitaInput.value = nikWanitaInput.value.replace(/\D/g, "").slice(0, 16);

        if (nikWanitaInput.value.length === 16 && !validateNIK(nikWanitaInput.value)) {
            showError("Format NIK Salah", "NIK wanita harus 16 digit angka.");
        }

        if (nikPriaInput.value === nikWanitaInput.value && nikWanitaInput.value.length === 16) {
            showError("NIK Tidak Valid", "NIK pria dan wanita tidak boleh sama.");
        }

        checkNikDuplicate("wanita", nikWanitaInput.value);
    });


    /* ============================
       VALIDASI NAMA (EDIT)
       ============================ */
    const namaInputs = document.querySelectorAll(
        "input[name='nama_pria'], input[name='nama_wanita'], input[name='nama_ayah_pria'], input[name='nama_ayah_wanita']"
    );

    namaInputs.forEach(input => {

        input.addEventListener("input", () => {
            input.value = input.value.replace(/[^a-zA-Z\s]/g, "");

            input.value = input.value
                .toLowerCase()
                .replace(/\b\w/g, c => c.toUpperCase());

            if (/\d/.test(input.value)) {
                showError("Format Nama Tidak Valid", "Nama tidak boleh mengandung angka.");
            }
        });
    });

});
