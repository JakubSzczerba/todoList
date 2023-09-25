import React from 'react';
import { render, fireEvent, waitFor, screen } from '@testing-library/react';
import axios from 'axios';
import App from './App';

// Mockowanie modułu axios
jest.mock('axios');

describe('App', () => {
  const mockTasks = [
    { id: 1, content: 'Zadanie 1', done: false },
    { id: 2, content: 'Zadanie 2', done: true }
  ];

  beforeEach(() => {
    // Ustalamy implementację funkcji axios.get używając beforeAll
    axios.get = jest.fn().mockResolvedValue({ data: JSON.stringify(mockTasks) });
    axios.post = jest.fn().mockResolvedValue({ data: JSON.stringify(mockTasks[0]) });
    axios.delete = jest.fn().mockResolvedValue({});
    axios.patch = jest.fn().mockResolvedValue({ data: JSON.stringify(mockTasks[0]) });
  });

  it('renders without crashing', () => {
    render(<App />);
  });

  it('displays tasks on load', async () => {
    render(<App />);

    await waitFor(() => {
      expect(screen.getByText('Zadanie 1')).toBeInTheDocument();
      expect(screen.getByText('Zadanie 2')).toBeInTheDocument();
    });
  });

  it('adds a new task', async () => {
    render(<App />);
    const input = screen.getByPlaceholderText('Dodaj nowe zadanie');
    const addButton = screen.getByText('Dodaj');

    fireEvent.change(input, { target: { value: 'Nowe zadanie' } });
    fireEvent.click(addButton);

    await waitFor(() => {
      expect(screen.getByText('Nowe zadanie')).toBeInTheDocument();
    });
  });

  it('marks a task as done', async () => {
    render(<App />);
    const checkbox = screen.getByLabelText('Zadanie 1') as HTMLInputElement;

    fireEvent.click(checkbox);

    await waitFor(() => {
      expect(checkbox.checked).toBe(true);
    });
  });

  it('deletes a task', async () => {
    render(<App />);
    const deleteButton = screen.getAllByText('Usuń')[0];

    fireEvent.click(deleteButton);

    await waitFor(() => {
      expect(screen.queryByText('Zadanie 1')).not.toBeInTheDocument();
    });
  });
});
