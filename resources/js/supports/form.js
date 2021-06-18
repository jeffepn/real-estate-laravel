export default class {
  constructor() {
    this.errors = {};
  }

  clearErrors() {
    this.errors = {};
  }

  hasError(field) {
    if (this.errors.hasOwnProperty(field)) {
      return true;
    }
    return false;
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

  firstError(field) {
    if (this.errors.hasOwnProperty(field)) {
      return this.errors[field][0];
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
