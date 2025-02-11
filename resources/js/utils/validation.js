// src/utils/validation.js
import * as Yup from 'yup';

export const loginSchema = Yup.object({
  email: Yup.string()
    .email('Invalid email address')
    .required('Email is required'),
  password: Yup.string()
    .min(6, 'Password must be at least 6 characters')
    .required('Password is required'),
});

export const registerSchema = Yup.object({
  name: Yup.string().required('Name is required'),
  email: Yup.string()
    .email('Invalid email address')
    .required('Email is required'),
  password: Yup.string()
    .min(6, 'Password must be at least 6 characters')
    .required('Password is required'),
});

export const bookingSchema = Yup.object({
  meeting_name: Yup.string().required('Meeting name is required'),
  meeting_datetime: Yup.date().required('Date and time are required'),
  duration: Yup.number().oneOf([30, 60, 90]).required('Duration is required'),
  members: Yup.number().min(1, 'Must be at least 1').required('Members is required'),
  meeting_room_id: Yup.number().required('Meeting room is required'),
});
