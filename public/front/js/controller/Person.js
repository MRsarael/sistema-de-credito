class Person {
    constructor(name, cpf, email, age) {
        this._name = name
        this._cpf = cpf
        this._email = email
        this._age = age
        this._id = null
    }

    toJson()
    {
        return {
            id: this._id,
            name: this._name,
            cpf: this._cpf,
            email: this._email,
            age: this._age,
        }
    }

    toJsonString()
    {
        return JSON.stringify(this.toJson())
    }

    set id(idPerson)
    {
        this._id = idPerson
    }

    get name()
    {
        return this._name
    }

    set name(name)
    {
        this._name = name
    }

    get cpf()
    {
        return this._cpf
    }

    set cpf(cpf)
    {
        this._cpf = cpf
    }

    get email()
    {
        return this._email
    }

    set email(email)
    {
        this._email = email
    }

    get age()
    {
        return this._age
    }

    set age(age)
    {
        this._age = age
    }
}
