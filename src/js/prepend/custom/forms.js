

//$('#subscribe').on('click',function(e) {
//    console.log("subscribe form shown");
//    form = document.getElementsByClassName("subscribe");
//    // Remove any existing errors that are being shown
//
//
//    //const fieldsWithErrors = form.querySelectorAll(".has-error");
//    //for (field of fieldsWithErrors) {
//    //    field.classList.remove("has-error");
//    //}
//
//    //// Remove success messages
//    //form.querySelectorAll(".form-success").remove();
//    //document.getElementsByClassName("form-errors").remove();
//
//   //removeMessages(form);
//});

$('#collapseForm').on('show.bs.collapse', function () {
    // do something…

    //$('a.collapsed').html(' ');
    $("ul.errors").remove();
    $(".form-group").removeClass('.has-error');
    $(".alert-danger").removeClass('.form-errors');
    $(".is-invalid").removeClass();
    // $(".alert-danger").remove();
    $(".form-success").remove();
    $('a#subscribe').addClass('show')
    $('i.fa.fa-window-close').show();

});
$('#collapseForm').on('hidden.bs.collapse', function () {
    // do something…
    console.log('hidden');
    //$('a.collapsed').html('Subscribe');

});
function lookForFormsToAjaxify() {
    const forms = document.getElementsByTagName("form");

    for (const form of forms) {
        if (!form.dataset.ajaxified) {
            form.dataset.ajaxified = true;
            form.addEventListener("submit", ajaxifyForm, false);
        }
    }
}

function ajaxifyForm(event) {
    const form = event.target;
    const data = new FormData(form);
    const request = new XMLHttpRequest();

    const method = form.getAttribute("method");
    const action = form.getAttribute("action");

    request.open(method, action ? action : window.location.href, true);
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    request.setRequestHeader("HTTP_X_REQUESTED_WITH", "XMLHttpRequest");
    request.onload = function () {
        removeMessages(form);

        if (request.status === 200) {
            const response = JSON.parse(request.response);

            if (response.success && response.finished) {
                // Reset the form so that the user may enter fresh information
                form.reset();

                // ============================================================
                // Uncomment this to have the form redirect to the success page
                // ============================================================
                // if (response.returnUrl) {
                //   window.location.href = response.returnUrl;
                // }

                renderFormSuccess(form);

            } else if (response.errors || response.formErrors) {
                renderErrors(response.errors, form);
                renderFormErrors(response.formErrors, form);
            }

            if (response.honeypot) {
                const honeypotInput = form.querySelector("input[name^=freeform_form_handle_]");
                honeypotInput.setAttribute("name", response.honeypot.name);
                honeypotInput.setAttribute("id", response.honeypot.name);
                honeypotInput.value = response.honeypot.hash;
            }

            unlockSubmit(form);
        } else {
            console.error(request);
        }

        unlockSubmit(form);
    };

    request.send(data);
    event.preventDefault();
}

function loadExternalForm(url, targetElement) {
    const request = new XMLHttpRequest();

    // Load the forms content into the #form-loader div
    request.open("GET", url, true);
    request.send();
    request.onload = function () {
        if (request.status === 200) {
            targetElement.innerHTML = request.response;

            // Activate all of the loaded scripts
            const loadedScripts = targetElement.getElementsByTagName('script')
            for (const script of loadedScripts) {
                if (script.getAttribute('src')) {
                    const newScript = document.createElement('script');
                    newScript.setAttribute('src', script.getAttribute('src'));
                    document.body.appendChild(newScript);
                } else {
                    eval(script.innerHTML)
                }
            }

            lookForFormsToAjaxify();
        } else {
            console.error(request);
        }
    };
}

/**
 * Remove the "disabled" state of the submit button upon successful submit
 *
 * @property form
 */
function unlockSubmit(form) {
    form.querySelector("button[type=submit]").removeAttribute("disabled");
    if (typeof grecaptcha !== 'undefined') {
        grecaptcha.reset();
    }
}

// Add remove prototypes
Element.prototype.remove = function () {
    this.parentElement.removeChild(this);
};

NodeList.prototype.remove = HTMLCollection.prototype.remove = function () {
    for (let i = this.length - 1; i >= 0; i--) {
        if (this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
};

if (window.renderFormSuccess === undefined) {
    function renderFormSuccess(form) {
        const successMessage = document.createElement("div");
        successMessage.classList.add("alert", "alert-success", "form-success");

        const paragraph = document.createElement("p");
        paragraph.classList.add("lead");
        paragraph.appendChild(document.createTextNode("Your on the list!"));

        $('.form-group').fadeOut();

        successMessage.appendChild(paragraph);

        form.insertBefore(successMessage, form.childNodes[0]);
    }
}

if (window.removeMessages === undefined) {
    function removeMessages(form) {
        // Remove any existing errors that are being shown
        form.querySelectorAll("ul.errors").remove();
        const fieldsWithErrors = form.querySelectorAll(".has-error");
        for (field of fieldsWithErrors) {
            field.classList.remove("has-error");
        }

        // Remove success messages
        form.querySelectorAll(".form-success").remove();
        document.getElementsByClassName("form-errors").remove();
    }
}

if (window.renderErrors === undefined) {
    /**
     * @param errors
     * @param form
     */
    function renderErrors(errors, form) {
        for (const key in errors) {
            if (!errors.hasOwnProperty(key) || !key) {
                continue;
            }

            const messages = errors[key];
            const errorsList = document.createElement("ul");
            errorsList.classList.add("errors", "help-block");

            for (const message of messages) {
                const listItem = document.createElement("li");
                listItem.appendChild(document.createTextNode(message));
                errorsList.appendChild(listItem);
            }

            const inputList = form.querySelectorAll("*[name=" + key + "], *[name='" + key + "[]']");
            for (const input of inputList) {
                input.parentElement.classList.add("has-error");
                input.parentElement.appendChild(errorsList);
            }
        }
    }
}

if (window.renderFormErrors === undefined) {
    function renderFormErrors(errors, form) {
        const errorBlock = document.createElement("div");
        errorBlock.classList.add("alert", "alert-danger", "form-errors");

        const paragraph = document.createElement("p");
        paragraph.classList.add("lead");
        paragraph.appendChild(document.createTextNode("This form has errors"));
        errorBlock.appendChild(paragraph);

        if (errors.length) {
            const errorsList = document.createElement("ul");
            for (const message of errors) {
                const listItem = document.createElement("li");
                listItem.appendChild(document.createTextNode(message));
                errorsList.appendChild(listItem);
            }

            errorBlock.appendChild(errorsList);
        }

        form.insertBefore(errorBlock, form.childNodes[0]);
    }
}

lookForFormsToAjaxify();

