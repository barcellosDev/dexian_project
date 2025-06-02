const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const dateRegex = /^\d{4}-\d{2}-\d{2}$/;

function validateEmail(email) {
    return emailRegex.test(email);
}

function formatPhoneNumber(phoneString) {
    let value = phoneString

  value = value.replace(/\D/g, '');

  if (value.length > 2) {
    value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
  }
  if (value.length > 9) {
    value = `${value.slice(0, 9)}-${value.slice(9)}`;
  }

  return value
}

function validateBirthDate(dateStr) {
    if (!dateRegex.test(dateStr)) return false;

    const [year, month, day] = dateStr.split('-').map(Number);
    const date = new Date(year, month - 1, day);

    return (
        date.getFullYear() === year &&
        date.getMonth() === month - 1 &&
        date.getDate() === day
    );
}

function setFieldToError(element) {
    if (!(element instanceof HTMLElement))
        return

    element.classList.add('field-with-error')

    setTimeout(() => {
        element.classList.remove('field-with-error')
    }, 1000 * 5)
}

export function useValidators() {
    return {
        validateEmail,
        validateBirthDate,
        setFieldToError,
        formatPhoneNumber
    }
}