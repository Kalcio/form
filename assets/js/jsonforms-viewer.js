import React from 'react';
import { createRoot } from 'react-dom/client';
import { JsonForms } from '@jsonforms/react';
import { materialRenderers } from '@jsonforms/material-renderers';

// Component using React.createElement instead of JSX (avoid setting vite.config.js).
const JsonFormsViewer = function(props) {
  return React.createElement(
    'div',
    { className: 'jsonforms-container' },
    React.createElement(JsonForms, {
      schema: props.schema,
      uischema: props.uischema,
      data: props.data || {},
      renderers: materialRenderers,
      onChange: props.onChange
    })
  );
};

// Function to initialize the viewer in any container.
window.renderJsonForm = function(containerId, jsonData, onSubmit) {
  const container = document.getElementById(containerId);
  if (!container) {
    console.error(`Container #${containerId} not found`);
    return;
  }

  try {
    // Parse JSON if necessary.
    const formData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;

    // Create state to store form data and errors.
    let currentData = formData.data || {};
    let currentErrors = [];

    const root = createRoot(container);

    root.render(
      React.createElement(JsonFormsViewer, {
        schema: formData.schema,
        uischema: formData.uischema,
        data: currentData,
        onChange: ({ data, errors }) => {
          currentData = data;
          currentErrors = errors;
        }
      })
    );

    // Expose method to get the current data and errors.
    return {
      getData: () => currentData,
      getErrors: () => currentErrors,
      submit: () => {
        if (onSubmit) {
          onSubmit(currentData);
        }
      }
    };
  } catch (error) {
    console.error('Error rendering JSON Forms:', error);
    container.innerHTML = `<div class="error">Error: ${error.message}</div>`;
    return null;
  }
};
