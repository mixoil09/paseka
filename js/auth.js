'use strict';
(function() {
    const getUsers = () => JSON.parse(localStorage.getItem('mock_users') || '[]');
    const saveUsers = (users) => localStorage.setItem('mock_users', JSON.stringify(users));
    if (getUsers().length === 0) saveUsers([{ name: 'Администратор', email: 'admin@paseka.ru', password: 'admin123', role: 'admin' }]);

    window.auth = {
        register: (name, email, password) => {
            const users = getUsers();
            if (users.find(u => u.email === email)) return { success: false, error: 'Email занят' };
            users.push({ name, email, password, role: 'user' });
            saveUsers(users); return { success: true };
        },
        login: (email, password) => {
            const user = getUsers().find(u => u.email === email && u.password === password);
            if (user) { localStorage.setItem('current_user', JSON.stringify(user)); return { success: true, role: user.role }; }
            return { success: false, error: 'Неверный email или пароль' };
        },
        getCurrentUser: () => JSON.parse(localStorage.getItem('current_user') || 'null')
    };
})();
