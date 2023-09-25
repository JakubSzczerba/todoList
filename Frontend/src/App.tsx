import React, {useState, useEffect} from 'react';
import './App.css';
import axios from 'axios';

const API_URL = 'http://todolist.local:8099/api/tasks';

function App() {
    const [tasks, setTasks] = useState<Task[]>([]);
    const [newTask, setNewTask] = useState('');

    interface Task {
        id: number;
        content: string;
        done: boolean;
    }

    useEffect(() => {
        axios
            .get<string[]>(API_URL)
            .then((response) => {
                const tasksData = JSON.parse(response.data[0]);
                setTasks(tasksData);
            })
            .catch((error) => {
                console.error('Błąd pobierania zadań:', error);
            });
    }, []);

    const handleAddTask = () => {
        if (newTask.trim() === '') return;
        const newTaskItem = {
            content: newTask,
            done: false,
        };

        axios.post<string>(API_URL, newTaskItem)
            .then((response) => {
                const newTaskData = JSON.parse(response.data[0]);
                setTasks([...tasks, newTaskData]);
                setNewTask('');
            })
            .catch((error) => {
                console.error('Błąd dodawania zadania:', error);
            });
    };

    const handleDeleteTask = (taskId: number) => {
        axios
            .delete(`${API_URL}/${taskId}`)
            .then(() => {
                const updatedTasks = tasks.filter((task) => task.id !== taskId);
                setTasks(updatedTasks);
            })
            .catch((error) => {
                console.error('Błąd usuwania zadania:', error);
            });
    };

    const handleToggleTask = (taskId: number) => {
        const taskToUpdate = tasks.find((task) => task.id === taskId);
        if (!taskToUpdate) return;

        const updatedTask = {
            ...taskToUpdate,
            done: !taskToUpdate.done,
        };

        axios
            .patch(`${API_URL}/${taskId}`, updatedTask)
            .then(() => {
                const updatedTasks = tasks.map((task) =>
                    task.id === taskId ? updatedTask : task
                );
                setTasks(updatedTasks);
            })
            .catch((error) => {
                console.error('Błąd aktualizacji zadania:', error);
            });
    };

    return (
        <div className="App">
            <div className="task-form">
                <input
                    type="text"
                    placeholder="Dodaj nowe zadanie"
                    value={newTask}
                    onChange={(e) => setNewTask(e.target.value)}
                />
                <button onClick={handleAddTask}>Dodaj</button>
            </div>
            <div className="task-list">
                {tasks.map((task) => (
                    <div key={task.id} className="task-item">
                        <input
                            type="checkbox"
                            checked={task.done}
                            onChange={() => !task.done && handleToggleTask(task.id)}
                        />
                        <p className={task.done ? 'completed' : ''}>{task.content}</p>
                        <button onClick={() => handleDeleteTask(task.id)}>Usuń</button>
                    </div>
                ))}
            </div>
        </div>
    );
}

export default App;
