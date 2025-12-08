// =====================================================
//  FORM VALIDATION (ADD & EDIT)
// =====================================================

document.addEventListener("DOMContentLoaded", function() {

    // ==========================
    // 1. VALIDASI NIK PRIA/WANITA
    // ==========================
    const nikPriaInput = document.querySelector("input[name='nik_pria']");
    const nikWanitaInput = document.querySelector("input[name='nik_wanita']");

    function validateNIKFormat(value) {
        return /^[0-9]{16}$/.test(value);
    }

    function showError(title, message) {
        Swal.fire({
            icon: "error",
            title: title,
            text: message,
            background: document.body.classList.contains("dark-mode") ? "#1f2937" : "#ffffff",
            color: document.body.classList.contains("dark-mode") ? "#ffffff" : "#000000",
        });
    }

    function checkNikDuplicate(type, nik) {
        if (nik.length !== 16) return;

        fetch("validate_nik.php?type=" + type + "&nik=" + nik)
            .then(res => res.json())
            .then(data => {
                if (data.status === "duplicate") {
                    showError(
                        "NIK Sudah Terdaftar",
                        "NIK mempelai " + type + " sudah digunakan dalam arsip lain."
                    );
                }
            });
    }

    if (nikPriaInput) {
        nikPriaInput.addEventListener("input", () => {
            nikPriaInput.value = nikPriaInput.value.replace(/\D/g, "").slice(0,16);

            if (nikPriaInput.value.length === 16 && !validateNIKFormat(nikPriaInput.value)) {
                showError("Format NIK Salah", "NIK pria harus 16 digit.");
            }

            if (nikPriaInput.value === nikWanitaInput.value && nikPriaInput.value.length === 16) {
                showError("NIK Tidak Valid", "NIK pria dan wanita tidak boleh sama.");
            }

            checkNikDuplicate("pria", nikPriaInput.value);
        });
    }

    if (nikWanitaInput) {
        nikWanitaInput.addEventListener("input", () => {
            nikWanitaInput.value = nikWanitaInput.value.replace(/\D/g, "").slice(0,16);

            if (nikWanitaInput.value.length === 16 && !validateNIKFormat(nikWanitaInput.value)) {
                showError("Format NIK Salah", "NIK wanita harus 16 digit.");
            }

            if (nikPriaInput.value === nikWanitaInput.value && nikWanitaInput.value.length === 16) {
                showError("NIK Tidak Valid", "NIK pria dan wanita tidak boleh sama.");
            }

            checkNikDuplicate("wanita", nikWanitaInput.value);
        });
    }


    // ==========================
    // 2. VALIDASI NOMOR AKTA
    // ==========================
    const nomorAktaInput = document.querySelector("input[name='nomor_akta']");

    if (nomorAktaInput) {
        nomorAktaInput.addEventListener("input", () => {

            nomorAktaInput.value = nomorAktaInput.value
                .toUpperCase()
                .replace(/[^A-Z0-9\-\/]/g, "")
                .replace(/\s+/g, "");

            if (nomorAktaInput.value.length < 5) return;

            fetch("../validate_akta.php?nomor=" + encodeURIComponent(nomorAktaInput.value))
                .then(res => res.json())
                .then(data => {
                    if (data.status === "duplicate") {
                        Swal.fire({
                            icon: "error",
                            title: "Nomor Akta Duplikat",
                            text: "Nomor Akta ini sudah terdaftar.",
                            background: document.body.classList.contains("dark-mode") ? "#1f2937" : "#ffffff",
                            color: document.body.classList.contains("dark-mode") ? "#ffffff" : "#000000",
                        });
                    }
                });
        });
    }


    // ==========================
    // 3. VALIDASI NAMA (HURUF-ONLY)
    // ==========================
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
                Swal.fire({
                    icon: "error",
                    title: "Format Nama Tidak Valid",
                    text: "Nama tidak boleh mengandung angka.",
                    background: document.body.classList.contains("dark-mode") ? "#1f2937" : "#ffffff",
                    color: document.body.classList.contains("dark-mode") ? "#ffffff" : "#000000",
                });
            }
        });
    });

});

