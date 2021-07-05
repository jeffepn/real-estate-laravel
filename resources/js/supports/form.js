export default class {
  constructor(data = {}) {
    this.data = data;
    this.errors = {};
  }
  // Fields

  get(field) {
    if (this.data.hasOwnProperty(field)) {
      return this.data[field];
    }
    return null;
  }

  clearErrors() {
    this.errors = {};
  }
  clearFields() {
    let aux = Object.assign({}, this.data);
    Object.keys(aux).forEach((key) => {
      if (this.has(aux, key)) {
        aux[key] = null;
      }
    });
    this.data = aux;
  }
  has(currentObject, field) {
    if (currentObject.hasOwnProperty(field)) {
      return true;
    }
    return false;
  }
  hasError(field) {
    return this.has(this.errors, field);
  }
  hasField(field) {
    return this.has(this.data, field);
  }

  setError(field, message) {
    let aux = Object.assign({}, this.errors);
    aux[field] = [message];
    this.errors = aux;
  }

  clearError(field) {
    let aux = Object.assign({}, this.errors);
    delete aux[field];
    this.errors = aux;
  }
  clearField(field) {
    let aux = Object.assign({}, this.data);
    aux[field] = null;
    this.errors = aux;
  }

  firstError(field) {
    if (this.errors.hasOwnProperty(field)) {
      return this.errors[field][0];
    }
    return "";
  }

  firstKeyErrorAny() {
    let keysErrors = Object.keys(this.errors);
    if (keysErrors.length) {
      return keysErrors[0];
    }
    return "";
  }

  isEmptyErrors() {
    return Object.keys(this.errors).length < 1;
  }

  isNotEmptyErrors() {
    return !this.isEmpty();
  }
}
