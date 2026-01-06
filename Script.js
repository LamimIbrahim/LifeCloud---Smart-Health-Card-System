const form = document.getElementById("regForm");
const successMsg = document.getElementById("successMsg");

const fields = [
    "fullName",
    "gender",
    "dob",
    "email",
    "address",
    "password",
    "confirmPassword",
];

const getEl = (id) => document.getElementById(id);
const errEl = (id) => document.getElementById(`err-${id}`);

function setError(id, msg) {
    const el = errEl(id);
    if (el) el.textContent = msg || "";
}

function clearAllErrors() {
    fields.forEach((f) => setError(f, ""));
}

function validatePasswordMatch() {
    const pass = getEl("password");
    const confirm = getEl("confirmPassword");
    if (!pass || !confirm) return;

    if (confirm.value && pass.value !== confirm.value) {
        confirm.setCustomValidity("Password did not match.");
    } else {
        confirm.setCustomValidity("");
    }
}

function showFieldError(input) {
    const id = input.id;
    if (!id || !fields.includes(id)) return;
    setError(id, input.validationMessage || "");
}

if (form) {
    // live validation
    fields.forEach((id) => {
        const el = getEl(id);
        if (!el) return;

        el.addEventListener("input", () => {
            if (id === "password" || id === "confirmPassword") {
                validatePasswordMatch();
            }
            if (el.checkValidity()) setError(id, "");
            else showFieldError(el);
        });

        el.addEventListener("blur", () => {
            if (id === "password" || id === "confirmPassword") {
                validatePasswordMatch();
            }
            showFieldError(el);
        });
    });

    // submit handler
    form.addEventListener("submit", (e) => {
        if (!successMsg) return;

        successMsg.hidden = true;
        successMsg.textContent = "";

        validatePasswordMatch();
        clearAllErrors();

        const ok = form.checkValidity();
        if (!ok) {
            e.preventDefault();
            form.reportValidity();
            fields.forEach((id) => {
                const el = getEl(id);
                if (el) showFieldError(el);
            });
            return;
        }


    });
}