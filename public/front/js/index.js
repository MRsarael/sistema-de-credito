(function () {
  'use strict'
})()

async function cpf(input) {
  var text = input.value
  text = text.replace(/\D/g,"")
  text = text.replace(/(\d{3})(\d)/,"$1.$2")
  text = text.replace(/(\d{3})(\d)/,"$1.$2")
  text = text.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
  input.value = text
}

function showMessage(type, msg)
{
  var myToastEl = document.getElementById('liveToast')
  var myToast = new bootstrap.Toast(myToastEl)

  switch (type) {
    case 'error':
      myToastEl.innerHTML = `<div class="toast-body bg-danger"><b>${msg}</b></div>`
    break

    case 'info':
      myToastEl.innerHTML = `<div class="toast-body bg-primary"><b>${msg}</b></div>`
    break
    
    case 'success':
      myToastEl.innerHTML = `<div class="toast-body bg-success"><b>${msg}</b></div>`
    break

    case 'warning':
      myToastEl.innerHTML = `<div class="toast-body bg-warning"><b>${msg}</b></div>`
    break
  }

  myToast.show()
}

function openLoad()
{
  if(document.getElementById('modal-load') == null) {
    const loadingEl = document.createElement("div")
    document.body.prepend(loadingEl)
  
    loadingEl.classList.add("modal")
    loadingEl.classList.add("fade")
    loadingEl.classList.add("modal-load-backdrop")
    loadingEl.style.zIndex = "99999999999999"
    loadingEl.setAttribute('id', 'modal-load')
    loadingEl.setAttribute('data-bs-backdrop', 'static')
    loadingEl.innerHTML = `<div class="modal-dialog modal-dialog-centered"><div class="spinner"></div></div>`
  
    const load = new bootstrap.Modal(document.getElementById('modal-load'), {
      backdrop: false
    })
    load.show()
  }
}

function closeLoad()
{
  const divModalLoad = document.getElementById('modal-load')
  if(divModalLoad) {
    const load = new bootstrap.Modal(divModalLoad, {})
    load.hide()
    divModalLoad.remove()
  }
}

