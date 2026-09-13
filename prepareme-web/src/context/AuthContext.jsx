import React, { createContext, useContext, useState, useEffect } from 'react';
import apiClient from '../api/client';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('prepareme_token') || null);
  const [loading, setLoading] = useState(true);

  // Initialize auth state
  useEffect(() => {
    const fetchCurrentUser = async () => {
      if (!token) {
        setLoading(false);
        return;
      }
      try {
        const response = await apiClient.get('/me');
        setUser(response.data.data);
        localStorage.setItem('prepareme_user', JSON.stringify(response.data.data));
      } catch (err) {
        console.error('Failed to load user session', err);
        localStorage.removeItem('prepareme_token');
        localStorage.removeItem('prepareme_user');
        setToken(null);
        setUser(null);
      } finally {
        setLoading(false);
      }
    };

    fetchCurrentUser();
  }, [token]);

  const login = async (email, password) => {
    const response = await apiClient.post('/auth/login', { email, password });
    const { token: receivedToken, user: receivedUser } = response.data.data;
    localStorage.setItem('prepareme_token', receivedToken);
    localStorage.setItem('prepareme_user', JSON.stringify(receivedUser));
    setToken(receivedToken);
    setUser(receivedUser);
    return receivedUser;
  };

  const register = async (name, email, password, password_confirmation) => {
    const response = await apiClient.post('/auth/register', {
      name,
      email,
      password,
      password_confirmation,
    });
    const { token: receivedToken, user: receivedUser } = response.data.data;
    localStorage.setItem('prepareme_token', receivedToken);
    localStorage.setItem('prepareme_user', JSON.stringify(receivedUser));
    setToken(receivedToken);
    setUser(receivedUser);
    return receivedUser;
  };

  const logout = async () => {
    try {
      if (token) {
        await apiClient.post('/auth/logout');
      }
    } catch (err) {
      console.warn('Logout network error', err);
    } finally {
      localStorage.removeItem('prepareme_token');
      localStorage.removeItem('prepareme_user');
      setToken(null);
      setUser(null);
    }
  };

  const updateProfile = async (data) => {
    const response = await apiClient.put('/me', data);
    const updated = response.data.data;
    setUser(updated);
    localStorage.setItem('prepareme_user', JSON.stringify(updated));
    return updated;
  };

  const refreshUser = async () => {
    if (!token) return null;
    const response = await apiClient.get('/me');
    setUser(response.data.data);
    return response.data.data;
  };

  const isAuthenticated = !!token && !!user;
  const isAdmin = isAuthenticated && user.role === 'admin';

  return (
    <AuthContext.Provider
      value={{
        user,
        token,
        loading,
        isAuthenticated,
        isAdmin,
        login,
        register,
        logout,
        updateProfile,
        refreshUser,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
