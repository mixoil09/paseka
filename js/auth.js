'use strict';

(function() {
    const getUsers = () => JSON.parse(localStorage.getItem('mock_users') || '[]');
    const saveUsers = (users) => localStorage.setItem('mock_users', JSON.stringify(users));
    
    // Создаем админа при первом запуске, если его нет
    if (getUsers().length === 0) {
        saveUsers([{ 
            id: 1, 
            name: 'Администратор', 
            email: 'admin@paseka.ru', 
            password: 'admin123', 
            role: 'admin' 
        }]);
    }

    window.auth = {
        register: (name, email, password) => {
            const users = getUsers();
            if (users.find(u => u.email === email)) {
                return { success: false, error: 'Этот Email уже занят' };
            }
            // Генерируем случайный ID на основе времени
            const newUser = { id: Date.now(), name, email, password, role: 'user' };
            users.push(newUser);
            saveUsers(users); 
            return { success: true };
        },
        
        login: (email, password) => {
            const users = getUsers();
            const user = users.find(u => u.email === email && u.password === password);
            
            if (user) { 
                localStorage.setItem('current_user', JSON.stringify(user)); 
                return { success: true, role: user.role }; 
            }
            return { success: false, error: 'Неверный email или пароль' };
        },
        
        logout: () => {
            localStorage.removeItem('current_user');
        },
        
        getCurrentUser: () => {
            return JSON.parse(localStorage.getItem('current_user') || 'null');
        }
    };
})();
