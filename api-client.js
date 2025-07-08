fetch('/submit', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    email: 'test.qa@example.com',
    first_name: 'Test',
    last_name: 'QA',
    date_of_birth: '1990-05-01',
    marketing_consent: true,
    marketing_lists: ['London', 'Edinburgh'],
    message: 'I would like to know more about your services.'
  })
})
  .then(res => res.json())
  .then(data => console.log(data));
  