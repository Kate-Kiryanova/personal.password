# personal.password
1C-Bitrix component for update personal password in personal section

# Код вызова компонента:
$APPLICATION->IncludeComponent(
    "flxmd:personal.password",
    "edit_password",
    Array(
        "COMPONENT_TEMPLATE" => "edit_password",
    )
);
