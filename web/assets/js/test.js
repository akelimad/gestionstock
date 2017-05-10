function Person(name, age, sex, parent, work, friends) {
    this.name = name;
    this.age = age;
    this.sex = sex;
    this.parent = parent;
    this.work = work;
    this.friends = friends;

    this.addFriend = function(name, age, sex, parent, work, friends) {
        this.friends.push(new Person(name, age, sex, parent, work, friends));
    };
}

//var p = new Person('akel', 23, 'm', 'aîné', 'dev', []);
//p.name="abdo";

var listPerson = [
    new Person('Sébastien', 23, 'm', 'aîné', 'JavaScripteur', []),
    new Person('Laurence', 19, 'f', 'soeur', 'Sous-officier', []),
    new Person('Ludovic', 9, 'm', 'frère', 'Etudiant', []),
    new Person('Pauline', 16, 'f', 'cousine', 'Etudiante', []),
    new Person('Guillaume', 16, 'm', 'cousin', 'Dessinateur', []),
];

//alert(p.name + ' a ' + p.age + ' ans');
