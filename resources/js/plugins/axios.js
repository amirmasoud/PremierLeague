import axios from 'axios'
import router from '~/router'
import swal from 'sweetalert2'

// Response interceptor
axios.interceptors.response.use(response => response, error => {
  const { status } = error.response

  if (status >= 500) {
    swal({
      type: 'error',
      title: "Oops!",
      text: "Something went wrong",
      reverseButtons: true,
      confirmButtonText: "Ok",
      cancelButtonText: "Cancel"
    })
  }

  return Promise.reject(error)
})
