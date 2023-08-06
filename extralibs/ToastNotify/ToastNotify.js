// Función para cargar el CSS de Font Awesome y definir el constructor de las notificaciones.
!(function () {
  var fontAwesomeCSS =
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css";
  var elementType = "link";
  let element;
  (element = document.createElement(elementType)),
    elementType === "script"
      ? (element.src = fontAwesomeCSS)
      : ((element.href = fontAwesomeCSS), (element.rel = "stylesheet")),
    document.head.appendChild(element);

  // Crear un elemento ul en el cuerpo del documento para contener las notificaciones.
  let notificationContainer = document.createElement("ul");
  document.body.insertBefore(notificationContainer, null);
  notificationContainer.className = "notytoast";

  // Constructor de las notificaciones emergentes (toasts).
  window.ToastNotify = class ToastNotify {
    constructor(type, options) {
      let removeToast = (toast) => {
        toast.classList.add("hide");
        toast.timeoutId && clearTimeout(toast.timeoutId);
        setTimeout(() => toast.remove(), 500);
      };

      // Definir los estilos y datos correspondientes a cada tipo de notificación.
      let toastStyles = [
        { type: "success", iconClass: "fa-circle-check", backgroundColor: "#0d8a28" },
        { type: "error", iconClass: "fa-circle-xmark", backgroundColor: "#bb2525" },
        { type: "warning", iconClass: "fa-triangle-exclamation", backgroundColor: "#E9BD0C" },
        { type: "info", iconClass: "fa-circle-info", backgroundColor: "#3498DB" },
      ];

      let toastData = toastStyles.find((t) => t.type == type);

      // Crear el elemento li que representa la notificación.
      let notificationList = document.querySelector(".notytoast");
      let toastElement = document.createElement("li");
      toastElement.className = "toastflo " + type;
      toastElement.innerHTML =
        '<div class="column"><i class="fa-solid ' +
        toastData.iconClass +
        '"></i><div><b>' +
        options.head.toUpperCase() +
        "</b><br><span>" +
        options.msg +
        '</span></div></div><i class="fa-solid fa-xmark" onclick="ToastRemove(this.parentElement)"></i>';
      notificationList.appendChild(toastElement);

      // Configurar el tiempo de duración de la notificación si se especifica en las opciones.
      if (options.timer !== undefined) {
        document.documentElement.style.setProperty(
          "--tiempo",
          options.timer / 1000 + "s"
        );
        toastElement.timeoutId = setTimeout(() => removeToast(toastElement), options.timer);
      } else {
        document.documentElement.style.setProperty("--tiempo", "0s");
      }
    }
  };
})();

// Función para remover una notificación al hacer clic en el botón de cerrar (x).
const ToastRemove = (toastElement) => {
  toastElement.classList.add("hide");
  toastElement.timeoutId && clearTimeout(toastElement.timeoutId);
  setTimeout(() => toastElement.remove(), 500);
};
