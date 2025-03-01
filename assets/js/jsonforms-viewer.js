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
      //readonly: true // For display only.
    })
  );
};

// Function to initialize the viewer in any container.
window.renderJsonForm = function(containerId, jsonData) {
  const container = document.getElementById(containerId);
  if (!container) {
    console.error(`Container #${containerId} not found`);
    return;
  }

  try {
    // Parse JSON if necessary.
    const formData = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
    const root = createRoot(container);

    root.render(
      React.createElement(JsonFormsViewer, {
        schema: formData.schema,
        uischema: formData.uischema,
        data: formData.data
      })
    );
  } catch (error) {
    console.error('Error rendering JSON Forms:', error);
    container.innerHTML = `<div class="error">Error: ${error.message}</div>`;
  }
};
