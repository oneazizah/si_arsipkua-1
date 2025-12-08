document.addEventListener("DOMContentLoaded", function () {

    const recordId = document.querySelector("#editForm").dataset.id;

    // -------------------------------------
    // Helper, Swal
    // -------------------------------------
    function showError(title, message) {
        Swal.fire({
            icon: "error",
            title: title,
            text: message,
            background: document.body.classList.contains("dark-mode") ? "#1f2937" : "#ffffff",
            color: document.body.classList.contains("dark-mode") ? "#ffffff" : "#000000",
        });
    }

    // -------------------------------------
    // VALIDASI NAMA
    // -------------------------------------
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
                showError("Format Nama Salah", "Nama tidak boleh berisi angka!");
            }
        });
    });

    // -------------------------------------
    // VALIDASI NIK PRIA & WANITA
    // -------------------------------------
    const nikPria = document.querySelector("input[name='nik_pria']");
    const nikWanita = document.querySelector("input[name='nik_wanita']");

    function validateNIK(nik) {
        return /^[0-9]{16}$/.test(nik);
    }

    function checkDuplicateNIK(nik, type) {
        if (nik.length !== 16) return;

        fetch("validate_nik_edit.php?nik=" + nik + "&type=" + type + "&id=" + recordId)
            .then(res => res.json())
            .then(data => {
                if (data.status === "duplicate") {
                    showError("NIK Sudah Ada", "NIK tersebut sudah digunakan arsip lain.");
                }
            });
    }

    nikPria.addEventListener("input", () => {
        nikPria.value = nikPria.value.replace(/\D/g, "").slice(0, 16);

        if (nikPria.value.length === 16 && !validateNIK(nikPria.value)) {
            showError("Format NIK Salah", "NIK harus 16 angka.");
        }

        if (nikPria.value === nikWanita.value && nikPria.value.length === 16) {
            showError("Data Tidak Valid", "NIK suami & istri tidak boleh sama.");
        }

        checkDuplicateNIK(nikPria.value, "pria");
    });

    nikWanita.addEventListener("input", () => {
        nikWanita.value = nikWanita.value.replace(/\D/g, "").slice(0, 16);

        if (nikWanita.value.length === 16 && !validateNIK(nikWanita.value)) {
            showError("Format NIK Salah", "NIK harus 16 angka.");
        }

        if (nikPria.value === nikWanita.value && nikWanita.value.length === 16) {
            showError("Data Tidak Valid", "NIK suami & istri tidak boleh sama.");
        }

        checkDuplicateNIK(nikWanita.value, "wanita");
    });

    // -------------------------------------
    // VALIDASI NOMOR AKTA
    // -------------------------------------
    const aktaInput = document.querySelector("input[name='nomor_akta']");

    aktaInput.addEventListener("input", () => {
        aktaInput.value = aktaInput.value.toUpperCase()
            .replace(/[^A-Z0-9\-\/]/g, "")
            .replace(/\s+/g, "");

        if (aktaInput.value.length < 5) return;

        fetch("validate_akta_edit.php?nomor=" + encodeURIComponent(aktaInput.value) + "&id=" + recordId)
            .then(res => res.json())
            .then(data => {
                if (data.status === "duplicate") {
                    showError("Nomor Akta Duplikat", "Nomor akta sudah digunakan arsip lain.");
                }
            });
    });

});
